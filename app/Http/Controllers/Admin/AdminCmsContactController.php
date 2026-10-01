<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContact;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCmsContactController extends Controller
{
    public function index()
    {
        $cms = CmsContact::first();
        if (!$cms) {
            $cms = new CmsContact();
            // Pre-fill contact & social media data from first admin user if available
            $user = User::first();
            if ($user) {
                $cms->email = $user->email;
                $cms->whatsapp = $user->sosmed_whatsapp;
                $cms->instagram = $user->sosmed_instagram;
                $cms->linkedin = $user->sosmed_linkedin;
                $cms->youtube = $user->sosmed_youtube;
                $cms->tiktok = $user->sosmed_tiktok;
            }
        }
        return view('admin.cms.contact.index', compact('cms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'teks_tombol' => 'nullable|string|max:255',
            'link_tombol' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $cms = CmsContact::first();
        if (!$cms) {
            $cms = new CmsContact();
        }

        $cms->fill($request->only([
            'judul',
            'deskripsi',
            'teks_tombol',
            'link_tombol',
            'whatsapp',
            'email',
            'instagram',
            'linkedin',
            'youtube',
            'tiktok',
            'alamat',
        ]));
        $cms->save();

        return redirect()->route('admin.cms.contact.index')->with('success', 'Konten CTA / Kontak berhasil diperbarui.');
    }
}
