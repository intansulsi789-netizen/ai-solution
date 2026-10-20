<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsHomepage;
use App\Services\SvgSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCmsLogoController extends Controller
{
    public function index()
    {
        $cms = CmsHomepage::first();
        if (!$cms) {
            $cms = new CmsHomepage([
                'site_logo_type'      => 'default',
                'site_name_highlight' => 'AI',
                'site_name_rest'      => 'Jogja Solutions',
            ]);
        }

        return view('admin.cms.logo.index', compact('cms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_logo_type'      => 'required|in:default,custom',
            'site_logo_file'      => 'nullable|file|mimes:png,jpeg,jpg,webp,svg|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:3072',
            'site_logo_url'       => 'nullable|string|max:500',
            'site_name_highlight' => 'nullable|string|max:50',
            'site_name_rest'      => 'nullable|string|max:100',
        ], [
            'site_logo_file.mimes'     => 'Format file logo harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'site_logo_file.mimetypes' => 'Format file logo tidak valid (harus gambar PNG, JPG, WEBP, atau SVG).',
            'site_logo_file.max'       => 'Ukuran file logo maksimal adalah 3MB.',
        ]);

        $cms = CmsHomepage::first();
        if (!$cms) {
            $cms = new CmsHomepage();
        }

        $logoPath = $cms->site_logo;

        // Jika memilih logo default (kembali ke ikon SVG bawaan)
        if ($request->site_logo_type === 'default') {
            if ($cms->site_logo && !str_starts_with($cms->site_logo, 'http') && Storage::disk('public')->exists($cms->site_logo)) {
                Storage::disk('public')->delete($cms->site_logo);
            }
            $logoPath = null;
        } else {
            // Jika memilih custom logo
            if ($request->hasFile('site_logo_file')) {
                $file = $request->file('site_logo_file');
                if (SvgSanitizer::isSvg($file)) {
                    $sanitized = SvgSanitizer::validateAndSanitizeUploadedFile($file, 3072);
                    if (!$sanitized['valid']) {
                        return back()->withInput()->withErrors(['site_logo_file' => $sanitized['error']]);
                    }
                    if ($cms->site_logo && !str_starts_with($cms->site_logo, 'http') && Storage::disk('public')->exists($cms->site_logo)) {
                        Storage::disk('public')->delete($cms->site_logo);
                    }
                    $filename = 'branding/' . uniqid('logo_svg_') . '.svg';
                    Storage::disk('public')->put($filename, $sanitized['sanitized_content']);
                    $logoPath = $filename;
                } else {
                    $imageInfo = @getimagesize($file->getRealPath());
                    if (!$imageInfo || !in_array($imageInfo['mime'], ['image/png', 'image/jpeg', 'image/webp'], true)) {
                        return back()->withInput()->withErrors(['site_logo_file' => 'File gambar yang diunggah tidak valid atau rusak.']);
                    }
                    if ($cms->site_logo && !str_starts_with($cms->site_logo, 'http') && Storage::disk('public')->exists($cms->site_logo)) {
                        Storage::disk('public')->delete($cms->site_logo);
                    }
                    $logoPath = $file->store('branding', 'public');
                }
            } elseif ($request->filled('site_logo_url')) {
                if ($cms->site_logo && !str_starts_with($cms->site_logo, 'http') && Storage::disk('public')->exists($cms->site_logo)) {
                    Storage::disk('public')->delete($cms->site_logo);
                }
                $logoPath = trim($request->site_logo_url);
            }
        }

        $cms->site_logo = $logoPath;
        $cms->site_logo_type = $request->site_logo_type;
        $cms->site_name_highlight = $request->filled('site_name_highlight') ? $request->site_name_highlight : 'AI';
        $cms->site_name_rest = $request->filled('site_name_rest') ? $request->site_name_rest : 'Jogja Solutions';
        $cms->save();

        return redirect()->route('admin.cms.logo.index')->with('success', 'Logo dan Identitas Website berhasil diperbarui.');
    }
}
