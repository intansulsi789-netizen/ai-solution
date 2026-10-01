<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSeo;
use Illuminate\Http\Request;

class AdminSeoController extends Controller
{
    public function index()
    {
        $cms = CmsSeo::firstOrNew([]);
        return view('admin.seo.index', compact('cms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $cms = CmsSeo::firstOrNew([]);
        $cms->meta_title = $request->meta_title;
        $cms->meta_description = $request->meta_description;
        $cms->save();

        return redirect()->route('admin.seo.index')->with('success', 'Pengaturan SEO berhasil diperbarui.');
    }
}
