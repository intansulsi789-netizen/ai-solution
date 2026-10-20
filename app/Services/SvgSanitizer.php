<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Http\UploadedFile;

class SvgSanitizer
{
    /**
     * Tag berbahaya yang dilarang berada di dalam file SVG.
     */
    protected static array $disallowedTags = [
        'script',
        'foreignobject',
        'iframe',
        'embed',
        'object',
        'applet',
        'meta',
        'link',
        'form',
        'input',
        'button',
        'select',
        'textarea',
        'audio',
        'video',
    ];

    /**
     * Skema protokol berbahaya yang dilarang pada atribut href, xlink:href, src, dll.
     */
    protected static array $disallowedProtocols = [
        'javascript:',
        'vbscript:',
        'data:text/html',
        'data:text/javascript',
        'data:application/javascript',
    ];

    /**
     * Periksa apakah berkas merupakan format SVG berdasarkan ekstensi atau MIME type.
     */
    public static function isSvg(UploadedFile|string $file): bool
    {
        if ($file instanceof UploadedFile) {
            $ext = strtolower($file->getClientOriginalExtension());
            $mime = strtolower($file->getClientMimeType());
            return $ext === 'svg' || str_contains($mime, 'svg');
        }

        return str_ends_with(strtolower($file), '.svg');
    }

    /**
     * Validasi dan sanitasi file UploadedFile SVG.
     * Mengembalikan array ['valid' => bool, 'sanitized_content' => ?string, 'error' => ?string].
     */
    public static function validateAndSanitizeUploadedFile(UploadedFile $file, int $maxKiloBytes = 5120): array
    {
        // 1. Cek ukuran file
        $fileSizeBytes = $file->getSize();
        if ($fileSizeBytes <= 0) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG kosong atau tidak terbaca.',
            ];
        }

        if ($fileSizeBytes > ($maxKiloBytes * 1024)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => "Ukuran file SVG melebihi batas maksimal ({$maxKiloBytes} KB).",
            ];
        }

        $realPath = $file->getRealPath();
        if (!$realPath || !file_exists($realPath)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'Gagal membaca file SVG yang diunggah.',
            ];
        }

        $content = @file_get_contents($realPath);
        if ($content === false || trim($content) === '') {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'Konten file SVG tidak valid atau kosong.',
            ];
        }

        return static::clean($content);
    }

    /**
     * Periksa dan sanitasi string XML SVG mentah.
     */
    public static function clean(string $svgContent): array
    {
        $trimmed = trim($svgContent);
        if ($trimmed === '') {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'Konten SVG kosong.',
            ];
        }

        // 1. Deteksi serangan XXE (XML External Entity)
        if (preg_match('/<!ENTITY/i', $trimmed) || preg_match('/<!DOCTYPE[^>]+SYSTEM/i', $trimmed) || preg_match('/<!DOCTYPE[^>]+PUBLIC/i', $trimmed)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG ditolak karena mengandung deklarasi XML Entity / DOCTYPE eksternal yang tidak aman (XXE).',
            ];
        }

        // 2. Deteksi script eksplisit atau payload script tag sebelum parsing
        if (preg_match('/<[^>]*:?script[\s>]/i', $trimmed) || preg_match('/<\/\s*:?script\s*>/i', $trimmed)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG ditolak karena mengandung tag <script> yang tidak aman.',
            ];
        }

        // 3. Deteksi event handler bawaan pada markup mentah (misal: onload=, onerror=, dll.)
        if (preg_match('/\bon[a-z]{3,}\s*=/i', $trimmed)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG ditolak karena mengandung event handler JavaScript inline yang tidak aman.',
            ];
        }

        // 4. Deteksi protokol javascript: di markup mentah
        if (preg_match('/(?:href|src|xlink:href)\s*=\s*["\']\s*javascript:/i', $trimmed)) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG ditolak karena mengandung tautan protokol javascript: yang tidak aman.',
            ];
        }

        // 5. Parse menggunakan DOMDocument dengan konfigurasi aman
        $dom = new DOMDocument();
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = true;

        $previousLibxmlState = libxml_use_internal_errors(true);
        if (\PHP_VERSION_ID < 80000 && function_exists('libxml_disable_entity_loader')) {
            libxml_disable_entity_loader(true);
        }

        // Cegah network entity loading
        $loaded = @$dom->loadXML($trimmed, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previousLibxmlState);

        if (!$loaded || !$dom->documentElement) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File bukan merupakan format XML/SVG yang valid atau berkas mengalami kerusakan.',
            ];
        }

        // 6. Pastikan root node adalah <svg>
        $rootName = strtolower($dom->documentElement->localName ?? $dom->documentElement->nodeName);
        if ($rootName !== 'svg') {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'Elemen utama berkas bukan tag <svg>. Berkas bukan gambar SVG yang sah.',
            ];
        }

        // 7. Bersihkan node berbahaya secara rekursif
        $hasFatalIssue = false;
        static::sanitizeDomNode($dom->documentElement, $hasFatalIssue);

        if ($hasFatalIssue) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'File SVG mengandung elemen yang tidak dapat disanitasi secara aman.',
            ];
        }

        // 8. Serialisasi hasil DOM yang aman
        $sanitizedSvg = $dom->saveXML($dom->documentElement);
        if (!$sanitizedSvg) {
            return [
                'valid' => false,
                'sanitized_content' => null,
                'error' => 'Gagal memproses dan menyimpan hasil sanitasi SVG.',
            ];
        }

        return [
            'valid' => true,
            'sanitized_content' => $sanitizedSvg,
            'error' => null,
        ];
    }

    /**
     * Sanitasi rekursif pada elemen DOM SVG.
     */
    protected static function sanitizeDomNode(DOMNode $node, bool &$hasFatalIssue): void
    {
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $tagName = strtolower($child->localName ?? $child->nodeName);

                // Hapus tag terlarang
                if (in_array($tagName, static::$disallowedTags, true)) {
                    $node->removeChild($child);
                    continue;
                }

                // Cek dan bersihkan atribut
                if ($child->hasAttributes()) {
                    $attrsToRemove = [];
                    foreach ($child->attributes as $attr) {
                        $attrName = strtolower($attr->nodeName);
                        $attrVal  = trim($attr->nodeValue);

                        // Hapus atribut event handler
                        if (str_starts_with($attrName, 'on') || preg_match('/^on[a-z]/i', $attrName)) {
                            $attrsToRemove[] = $attr->nodeName;
                            continue;
                        }

                        // Hapus atribut yang memuat protokol berbahaya
                        $normalizedVal = strtolower(preg_replace('/\s+/', '', $attrVal));
                        foreach (static::$disallowedProtocols as $proto) {
                            if (str_starts_with($normalizedVal, $proto)) {
                                $attrsToRemove[] = $attr->nodeName;
                                break;
                            }
                        }

                        // Proteksi <use> href eksternal (mencegah SSRF / external inclusion)
                        if ($tagName === 'use' && in_array($attrName, ['href', 'xlink:href'], true)) {
                            if (
                                str_starts_with($attrVal, 'http://') ||
                                str_starts_with($attrVal, 'https://') ||
                                str_starts_with($attrVal, '//')
                            ) {
                                $attrsToRemove[] = $attr->nodeName;
                            }
                        }
                    }

                    foreach ($attrsToRemove as $rmAttr) {
                        $child->removeAttribute($rmAttr);
                    }
                }

                // Inspeksi isi tag <style> jika ada
                if ($tagName === 'style') {
                    $styleText = strtolower($child->nodeValue);
                    if (
                        str_contains($styleText, 'expression(') ||
                        str_contains($styleText, 'javascript:') ||
                        str_contains($styleText, '@import') ||
                        str_contains($styleText, 'behavior:') ||
                        str_contains($styleText, '-moz-binding')
                    ) {
                        $node->removeChild($child);
                        continue;
                    }
                }

                // Lakukan rekursi ke child nodes
                static::sanitizeDomNode($child, $hasFatalIssue);
            }
        }
    }
}
