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
        $rules = [
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
        ];

        $messages = [
            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.max'      => 'Email tidak boleh lebih dari :max karakter.',
            'email.string'   => 'Email harus berupa teks.',

            // Judul CTA
            'judul.required' => 'Judul CTA wajib diisi.',
            'judul.string'   => 'Judul CTA harus berupa teks.',
            'judul.max'      => 'Judul CTA tidak boleh lebih dari :max karakter.',
            'judul.min'      => 'Judul CTA minimal :min karakter.',

            // Deskripsi CTA
            'deskripsi.required' => 'Deskripsi CTA wajib diisi.',
            'deskripsi.string'   => 'Deskripsi CTA harus berupa teks.',
            'deskripsi.max'      => 'Deskripsi CTA tidak boleh lebih dari :max karakter.',

            // Teks Tombol CTA
            'teks_tombol.required' => 'Teks tombol CTA wajib diisi.',
            'teks_tombol.string'   => 'Teks tombol CTA harus berupa teks.',
            'teks_tombol.max'      => 'Teks tombol CTA tidak boleh lebih dari :max karakter.',

            // Link Tombol CTA
            'link_tombol.required' => 'Link tombol CTA wajib diisi.',
            'link_tombol.string'   => 'Link tombol CTA harus berupa teks.',
            'link_tombol.max'      => 'Link tombol CTA tidak boleh lebih dari :max karakter.',
            'link_tombol.url'      => 'Format link tombol CTA tidak valid.',

            // WhatsApp
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.string'   => 'Nomor WhatsApp harus berupa teks.',
            'whatsapp.max'      => 'Nomor WhatsApp tidak boleh lebih dari :max karakter.',
            'whatsapp.numeric'  => 'Nomor WhatsApp harus berupa angka.',

            // Alamat
            'alamat.required' => 'Alamat kantor wajib diisi.',
            'alamat.string'   => 'Alamat kantor harus berupa teks.',
            'alamat.max'      => 'Alamat kantor tidak boleh lebih dari :max karakter.',

            // Media Sosial: Instagram
            'instagram.required' => 'Tautan Instagram wajib diisi.',
            'instagram.string'   => 'Instagram harus berupa teks.',
            'instagram.max'      => 'Instagram tidak boleh lebih dari :max karakter.',
            'instagram.url'      => 'Format tautan Instagram tidak valid.',

            // Media Sosial: LinkedIn
            'linkedin.required' => 'Tautan LinkedIn wajib diisi.',
            'linkedin.string'   => 'LinkedIn harus berupa teks.',
            'linkedin.max'      => 'LinkedIn tidak boleh lebih dari :max karakter.',
            'linkedin.url'      => 'Format tautan LinkedIn tidak valid.',

            // Media Sosial: YouTube
            'youtube.required' => 'Tautan YouTube wajib diisi.',
            'youtube.string'   => 'YouTube harus berupa teks.',
            'youtube.max'      => 'YouTube tidak boleh lebih dari :max karakter.',
            'youtube.url'      => 'Format tautan YouTube tidak valid.',

            // Media Sosial: TikTok
            'tiktok.required' => 'Tautan TikTok wajib diisi.',
            'tiktok.string'   => 'TikTok harus berupa teks.',
            'tiktok.max'      => 'TikTok tidak boleh lebih dari :max karakter.',
            'tiktok.url'      => 'Format tautan TikTok tidak valid.',

            // Fallback aturan umum dalam bahasa Indonesia
            'required'    => ':Attribute wajib diisi.',
            'email'       => 'Format :attribute tidak valid.',
            'max.string'  => ':Attribute tidak boleh lebih dari :max karakter.',
            'max.numeric' => ':Attribute tidak boleh lebih dari :max.',
            'min.string'  => ':Attribute minimal :min karakter.',
            'min.numeric' => ':Attribute minimal :min.',
            'string'      => ':Attribute harus berupa teks.',
            'numeric'     => ':Attribute harus berupa angka.',
            'url'         => 'Format tautan :attribute tidak valid.',
        ];

        $customAttributes = [
            'judul'       => 'Judul CTA',
            'deskripsi'   => 'Deskripsi CTA',
            'teks_tombol' => 'Teks tombol CTA',
            'link_tombol' => 'Link tombol CTA',
            'whatsapp'    => 'Nomor WhatsApp',
            'email'       => 'Email',
            'alamat'      => 'Alamat',
            'instagram'   => 'Instagram',
            'linkedin'    => 'LinkedIn',
            'youtube'     => 'YouTube',
            'tiktok'      => 'TikTok',
        ];

        $request->validate($rules, $messages, $customAttributes);

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
