<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsHomepage;
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
            'site_logo_file'      => 'nullable|file|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:3072',
            'site_logo_url'       => 'nullable|string|max:500',
            'site_name_highlight' => 'nullable|string|max:50',
            'site_name_rest'      => 'nullable|string|max:100',
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
                if ($cms->site_logo && !str_starts_with($cms->site_logo, 'http') && Storage::disk('public')->exists($cms->site_logo)) {
                    Storage::disk('public')->delete($cms->site_logo);
                }
                $logoPath = $request->file('site_logo_file')->store('branding', 'public');
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
