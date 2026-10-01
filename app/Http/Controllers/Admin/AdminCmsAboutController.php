<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAbout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCmsAboutController extends Controller
{
    public function index()
    {
        $cms = CmsAbout::firstOrNew([]);
        return view('admin.cms.about.index', compact('cms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nama_ceo' => 'nullable|string|max:255',
            'jabatan_ceo' => 'nullable|string|max:255',
            'deskripsi_ceo' => 'nullable|string',
            'foto_ceo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $cms = CmsAbout::first();
        if (!$cms) {
            $cms = new CmsAbout();
        }

        $data = $request->only([
            'judul',
            'deskripsi',
            'nama_ceo',
            'jabatan_ceo',
            'deskripsi_ceo',
        ]);

        if ($request->hasFile('foto_ceo')) {
            if ($cms->foto_ceo && Storage::disk('public')->exists($cms->foto_ceo)) {
                // Jangan hapus foto profil user asli, kecuali kita memisahkan direktori,
                // tapi aman nya biarkan saja karena bisa jadi foto CMS berbeda dari foto Profil,
                // atau hapus foto lama JIKA path-nya bukan path default User profile.
                // Lebih baik simpan ke folder 'cms/about'.
                if (strpos($cms->foto_ceo, 'cms/about') !== false) {
                    Storage::disk('public')->delete($cms->foto_ceo);
                }
            }
            $data['foto_ceo'] = $request->file('foto_ceo')->store('cms/about', 'public');
        }

        $cms->fill($data);
        $cms->save();

        return redirect()->route('admin.cms.about.index')->with('success', 'Konten Tentang Kami berhasil diperbarui.');
    }
}
