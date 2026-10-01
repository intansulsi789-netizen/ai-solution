<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:articles,slug|max:255',
            'kategori'    => 'nullable|string|max:100',
            'tanggal'     => 'nullable|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'ringkasan'   => 'nullable|string',
            'isi_artikel' => 'nullable|string',
            'status'      => 'required|in:draft,published',
        ]);

        $data = $request->only(['judul', 'slug', 'kategori', 'tanggal', 'ringkasan', 'isi_artikel', 'status']);
        
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('articles', 'public');
        }

        $data['is_active'] = $request->has('is_active');

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:articles,slug,' . $article->id . '|max:255',
            'kategori'    => 'nullable|string|max:100',
            'tanggal'     => 'nullable|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'ringkasan'   => 'nullable|string',
            'isi_artikel' => 'nullable|string',
            'status'      => 'required|in:draft,published',
        ]);

        $data = $request->only(['judul', 'slug', 'kategori', 'tanggal', 'ringkasan', 'isi_artikel', 'status']);
        
        if ($request->hasFile('gambar')) {
            if ($article->gambar && !str_starts_with($article->gambar, 'http')) {
                Storage::disk('public')->delete($article->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('articles', 'public');
        }

        $data['is_active'] = $request->has('is_active');

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        if ($article->gambar && !str_starts_with($article->gambar, 'http')) {
            Storage::disk('public')->delete($article->gambar);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
