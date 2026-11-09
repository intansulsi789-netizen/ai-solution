<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('nomor')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $availableIcons = Service::availableIcons();
        return view('admin.services.create', compact('availableIcons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan'      => 'required|string|max:255',
            'slug'              => 'required|string|unique:services,slug|max:255',
            'nomor'             => 'nullable|integer',
            'icon'              => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_lengkap' => 'nullable|string',
            'is_active'         => 'boolean',
        ]);

        $data = $request->all();
        $data['nomor'] = $request->filled('nomor') ? (int) $request->nomor : ((Service::max('nomor') ?? 0) + 1);
        $data['icon'] = $request->input('icon', '');
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        } else {
            $data['image'] = null;
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $availableIcons = Service::availableIcons();
        return view('admin.services.edit', compact('service', 'availableIcons'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'nama_layanan'      => 'required|string|max:255',
            'slug'              => 'required|string|unique:services,slug,'.$service->id.'|max:255',
            'nomor'             => 'nullable|integer',
            'icon'              => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_lengkap' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['nomor'] = $request->filled('nomor') ? (int) $request->nomor : $service->nomor;
        $data['icon'] = $request->has('icon') ? $request->input('icon', '') : ($service->icon ?? '');
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Opsi hapus gambar
        if ($request->boolean('remove_image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        } else {
            // Pertahankan gambar lama yang sudah tersimpan
            unset($data['image']);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
