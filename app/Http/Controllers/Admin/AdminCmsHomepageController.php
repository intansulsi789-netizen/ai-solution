<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsHomepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCmsHomepageController extends Controller
{
    public function index()
    {
        $cms = CmsHomepage::first();
        if (!$cms) {
            $cms = new CmsHomepage();
        }
        return view('admin.cms.homepage.index', compact('cms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_badge' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hero_btn1_text' => 'nullable|string|max:255',
            'hero_btn1_link' => 'nullable|string|max:255',
            'hero_btn2_text' => 'nullable|string|max:255',
            'hero_btn2_link' => 'nullable|string|max:255',
            'partner_title' => 'nullable|string|max:255',
        ]);

        $cms = CmsHomepage::first();
        if (!$cms) {
            $cms = new CmsHomepage();
        }

        $data = $request->only([
            'hero_badge',
            'hero_title',
            'hero_description',
            'hero_btn1_text',
            'hero_btn1_link',
            'hero_btn2_text',
            'hero_btn2_link',
            'partner_title'
        ]);

        if ($request->hasFile('hero_background')) {
            if ($cms->hero_background && Storage::disk('public')->exists($cms->hero_background)) {
                Storage::disk('public')->delete($cms->hero_background);
            }
            $data['hero_background'] = $request->file('hero_background')->store('cms', 'public');
        }

        $cms->fill($data);
        
        $cms->save();

        return redirect()->route('admin.cms.homepage.index')->with('success', 'Konten Homepage berhasil diperbarui.');
    }
}
