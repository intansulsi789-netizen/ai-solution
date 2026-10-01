<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('urutan')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'logo_file' => 'required|file|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_file')) {
            $logoPath = $request->file('logo_file')->store('partners', 'public');
        }

        Partner::create([
            'nama'      => $request->nama,
            'logo'      => $logoPath,
            'urutan'    => $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:150',
            'urutan'    => 'required|integer|min:0',
            'logo_file' => 'nullable|file|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:2048',
        ]);

        $logoPath = $partner->logo; // keep existing by default

        if ($request->hasFile('logo_file')) {
            // delete old file if it's a stored path (not a URL)
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $request->file('logo_file')->store('partners', 'public');
        }

        $partner->update([
            'nama'      => $request->nama,
            'logo'      => $logoPath,
            'urutan'    => $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        // delete uploaded file if it's local
        if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus.');
    }

    /**
     * AJAX endpoint: update display order
     */
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $pos => $id) {
            Partner::where('id', $id)->update(['urutan' => $pos + 1]);
        }

        return response()->json(['success' => true]);
    }
}
