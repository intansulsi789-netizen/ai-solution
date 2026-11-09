<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Daftar tag HTML yang diperbolehkan (allowlist).
     */
    protected static array $allowedTags = [
        // Heading
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        // Text formatting
        'p', 'br', 'hr', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del', 'sub', 'sup', 'small', 'mark',
        // Quotes & Code
        'blockquote', 'pre', 'code', 'q', 'cite',
        // Lists
        'ul', 'ol', 'li',
        // Links & Media
        'a', 'img', 'figure', 'figcaption',
        // Tables
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
        // Containers & Spans
        'div', 'span',
    ];

    /**
     * Atribut yang diperbolehkan secara umum pada semua tag allowlist.
     */
    protected static array $allowedGlobalAttributes = [
        'class', 'id', 'title', 'lang', 'dir', 'style',
    ];

    /**
     * Atribut spesifik per tag.
     */
    protected static array $allowedTagAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading'],
        'th' => ['colspan', 'rowspan', 'scope'],
        'td' => ['colspan', 'rowspan'],
        'ol' => ['start', 'type', 'reversed'],
        'li' => ['value'],
    ];

    /**
     * Tag yang harus dihapus seluruhnya beserta konten di dalamnya.
     */
    protected static array $dangerousTags = [
        'script', 'style', 'iframe', 'frame', 'frameset', 'object', 'embed',
        'applet', 'meta', 'link', 'base', 'form', 'input', 'button', 'select',
        'textarea', 'svg', 'math', 'canvas', 'video', 'audio', 'source', 'track'
    ];

    /**
     * Properti CSS yang aman untuk inline style.
     */
    protected static array $safeStyleProperties = [
        'text-align', 'font-size', 'font-weight', 'font-style', 'font-family',
        'color', 'background-color', 'text-decoration', 'line-height',
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'width', 'height', 'max-width', 'border', 'border-collapse'
    ];

    /**
     * Membersihkan string HTML dari elemen berbahaya (Stored XSS).
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // Hapus null byte
        $html = str_replace(chr(0), '', $html);

        // Jika tidak ada karakter tag HTML, return langsung
        if (!str_contains($html, '<') && !str_contains($html, '>')) {
            return $html;
        }

        $prevInternalErrors = libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        // Bungkus dengan meta charset utf-8 dan div root
        $wrapper = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><div id="__sanitizer_root__">' . $html . '</div></body></html>';

        $dom->loadHTML($wrapper, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $root = $dom->getElementById('__sanitizer_root__');
        if (!$root) {
            libxml_clear_errors();
            libxml_use_internal_errors($prevInternalErrors);
            return '';
        }

        static::sanitizeNode($root);

        // Render kembali innerHTML dari root div
        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        libxml_clear_errors();
        libxml_use_internal_errors($prevInternalErrors);

        return trim($result);
    }

    /**
     * Membersihkan teks polos (ringkasan, metadata, takeaway) dari tag HTML dan injeksi XSS,
     * dengan tetap mempertahankan simbol matematis seperti '<50%', '>10%', '<=', '>='.
     */
    public static function cleanPlainText(?string $text): string
    {
        if ($text === null || trim($text) === '') {
            return '';
        }

        // 1. Hapus null byte
        $text = str_replace(chr(0), '', $text);

        // 2. Buang tag berbahaya beserta isinya jika ada yang menyuntikkan script/iframe/style/svg/dsb
        $text = preg_replace('/<(script|style|iframe|object|embed|svg|applet)\b[^>]*>.*?<\/\1>/is', '', $text);
        $text = preg_replace('/<(script|style|iframe|object|embed|svg|applet)\b[^>]*>/is', '', $text);

        // 3. Buang tag HTML umum (misal <b>, </b>, <p>, </div>, <img ...>, dll) 
        // Pola hanya mencocokkan jika setelah '<' atau '</' adalah huruf alfabet [a-zA-Z] atau '!' (comment/cdata)
        // Sehingga karakter seperti '<50%', '< 20', '<=', '<$' TIDAK akan terpotong.
        $text = preg_replace('/<\/?([a-zA-Z][a-zA-Z0-9\-_:]*|!--[\s\S]*?--)(?:\s+[^<>]*)?>/is', '', $text);

        return trim($text);
    }

    /**
     * Membersihkan isi_artikel baik dalam format JSON (Quill/Structured) maupun HTML mentah.
     */
    public static function cleanIsiArtikel(?string $isiArtikel): string
    {
        if ($isiArtikel === null || trim($isiArtikel) === '') {
            return '';
        }

        $decoded = json_decode($isiArtikel, true);
        if (is_array($decoded)) {
            if (isset($decoded['content'])) {
                $decoded['content'] = static::clean($decoded['content']);
            }
            if (isset($decoded['takeaway'])) {
                $decoded['takeaway'] = static::cleanPlainText($decoded['takeaway']);
            }
            if (isset($decoded['readTime'])) {
                $decoded['readTime'] = static::cleanPlainText($decoded['readTime']);
            }
            return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return static::clean($isiArtikel);
    }

    /**
     * Rekursif menyaring node DOM: membuang tag berbahaya dan membersihkan atribut.
     */
    protected static function sanitizeNode(\DOMNode $node): void
    {
        $childNodes = [];
        foreach ($node->childNodes as $child) {
            $childNodes[] = $child;
        }

        foreach ($childNodes as $child) {
            if ($child instanceof \DOMComment) {
                $node->removeChild($child);
                continue;
            }

            if ($child instanceof \DOMElement) {
                $tagName = strtolower($child->nodeName);

                // 1. Tag berbahaya: hapus seluruh elemen beserta konten anaknya
                if (in_array($tagName, static::$dangerousTags, true)) {
                    $node->removeChild($child);
                    continue;
                }

                // 2. Tag bukan allowlist: unwrap anak-anaknya, lalu buang tag induknya
                if (!in_array($tagName, static::$allowedTags, true)) {
                    static::sanitizeNode($child);
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                // 3. Tag diperbolehkan: bersihkan atributnya
                static::sanitizeAttributes($child);

                // Rekursif untuk konten di dalamnya
                static::sanitizeNode($child);
            }
        }
    }

    /**
     * Menyaring atribut dari DOMElement: memblokir on*, memvalidasi URL, dan menyaring style.
     */
    protected static function sanitizeAttributes(\DOMElement $element): void
    {
        $tagName = strtolower($element->nodeName);
        $allowedAttrs = array_merge(
            static::$allowedGlobalAttributes,
            static::$allowedTagAttributes[$tagName] ?? []
        );

        $attrsToRemove = [];

        foreach ($element->attributes as $attr) {
            $attrName = strtolower($attr->nodeName);
            $attrValue = $attr->nodeValue;

            // Blokir semua event handler: on* (onload, onerror, onclick, dll)
            if (str_starts_with($attrName, 'on')) {
                $attrsToRemove[] = $attr->nodeName;
                continue;
            }

            // Blokir atribut yang bukan allowlist
            if (!in_array($attrName, $allowedAttrs, true)) {
                $attrsToRemove[] = $attr->nodeName;
                continue;
            }

            // Validasi URL untuk href dan src
            if (in_array($attrName, ['href', 'src'], true)) {
                if (!static::isSafeUrl($attrValue)) {
                    $attrsToRemove[] = $attr->nodeName;
                    continue;
                }
            }

            // Validasi inline style
            if ($attrName === 'style') {
                $cleanedStyle = static::sanitizeStyle($attrValue);
                if ($cleanedStyle === '') {
                    $attrsToRemove[] = $attr->nodeName;
                } else {
                    $element->setAttribute('style', $cleanedStyle);
                }
                continue;
            }

            // Atribut rel otomatis pada target _blank untuk mencegah tabnabbing
            if ($tagName === 'a' && $attrName === 'target' && strtolower($attrValue) === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        foreach ($attrsToRemove as $attrName) {
            $element->removeAttribute($attrName);
        }
    }

    /**
     * Validasi keamanan skema URL (memblokir javascript:, vbscript:, data berbahaya, dll).
     */
    public static function isSafeUrl(?string $url): bool
    {
        if ($url === null) {
            return false;
        }

        $trimmed = trim($url);
        if ($trimmed === '') {
            return false;
        }

        // Hapus karakter kontrol dan whitespace di dalam string
        $cleaned = preg_replace('/[\x00-\x1F\x7F\s]+/u', '', $trimmed);

        // Skema berbahaya yang selalu diblokir
        if (preg_match('/^(javascript|vbscript|file):/i', $cleaned)) {
            return false;
        }

        // Penanganan skema data: (hanya perbolehkan gambar raster/webp aman, blokir data:text/html atau data:image/svg+xml)
        if (str_starts_with(strtolower($cleaned), 'data:')) {
            return (bool) preg_match('/^data:image\/(jpeg|jpg|png|gif|webp);base64,[A-Za-z0-9+\/=\s]+$/i', $trimmed);
        }

        // Relative URL (/..., ./..., #..., ?...)
        if (preg_match('/^(\/|\#|\?|\.\/|\.\.\/)/', $trimmed)) {
            return true;
        }

        // Absolute URL: http, https, mailto, tel
        if (preg_match('/^(https?|mailto|tel):/i', $trimmed)) {
            if (preg_match('/^https?:\/\//i', $trimmed)) {
                return (bool) filter_var($trimmed, FILTER_VALIDATE_URL);
            }
            return true;
        }

        // Path relatif tanpa awalan slash (contoh: "images/photo.jpg")
        if (!preg_match('/^[a-z0-9+.-]+:/i', $trimmed)) {
            return true;
        }

        return false;
    }

    /**
     * Menyaring deklarasi CSS inline untuk memblokir expression, url(), @import, dsb.
     */
    public static function sanitizeStyle(?string $style): string
    {
        if ($style === null || trim($style) === '') {
            return '';
        }

        // Decode entitas HTML jika ada
        $style = html_entity_decode($style, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Blokir pola berbahaya
        if (preg_match('/(expression|behavior|javascript|vbscript|url|@import|\\\)/i', $style)) {
            return '';
        }

        $cleanDeclarations = [];
        $declarations = explode(';', $style);
        foreach ($declarations as $decl) {
            $decl = trim($decl);
            if ($decl === '') {
                continue;
            }
            $parts = explode(':', $decl, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $prop = strtolower(trim($parts[0]));
            $val = trim($parts[1]);

            if (in_array($prop, static::$safeStyleProperties, true)) {
                // Pastikan nilai hanya memuat karakter aman (huruf, angka, spasi, %, px, em, rem, #, koma, strip, kurung fungsi rgb/rgba)
                if (preg_match('/^[a-zA-Z0-9\s#%.,\-()]+$/', $val) && !preg_match('/(expression|javascript|url)/i', $val)) {
                    $cleanDeclarations[] = $prop . ': ' . $val;
                }
            }
        }

        return implode('; ', $cleanDeclarations);
    }
}

