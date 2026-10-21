<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ArticleImageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        Article::autoPublishOverdue();

        $query = Article::query();

        // Pencarian judul / kategori / slug
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                  ->orWhere('kategori', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        // Filter status artikel
        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'scheduled'])) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 10 artikel per halaman
        $articles = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Untuk kalender jadwal, gunakan seluruh data artikel
        $allArticlesForCalendar = Article::orderBy('created_at', 'desc')->get();
        $calendarArticles = $this->formatArticlesForCalendar($allArticlesForCalendar);

        $activeTab = $request->query('tab', $request->query('view', 'table'));
        $scheduledCount = Article::where('status', 'scheduled')->count();
        $publishedCount = Article::where('status', 'published')->count();
        $draftCount     = Article::where('status', 'draft')->count();

        return view('admin.articles.index', compact(
            'articles',
            'calendarArticles',
            'activeTab',
            'scheduledCount',
            'publishedCount',
            'draftCount'
        ));
    }

    public function calendar(Request $request)
    {
        Article::autoPublishOverdue();
        $articles = Article::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $allArticlesForCalendar = Article::orderBy('created_at', 'desc')->get();
        $calendarArticles = $this->formatArticlesForCalendar($allArticlesForCalendar);
        $activeTab = 'calendar';
        $scheduledCount = Article::where('status', 'scheduled')->count();
        $publishedCount = Article::where('status', 'published')->count();
        $draftCount     = Article::where('status', 'draft')->count();

        return view('admin.articles.index', compact(
            'articles',
            'calendarArticles',
            'activeTab',
            'scheduledCount',
            'publishedCount',
            'draftCount'
        ));
    }

    protected function formatArticlesForCalendar($articles)
    {
        return $articles->map(function ($a) {
            $status = $a->status ?? 'published';
            $date = null;
            $time = '';
            $fullDateTime = '';

            if ($a->published_at) {
                $dt = $a->published_at->timezone('Asia/Jakarta');
                $date = $dt->format('Y-m-d');
                $time = $dt->format('H:i') . ' WIB';
                $fullDateTime = $dt->locale('id')->translatedFormat('d F Y, H:i') . ' WIB';
            } elseif ($status === 'published' && !empty($a->tanggal)) {
                try {
                    $parsed = Carbon::parse($a->tanggal, 'Asia/Jakarta');
                    $date = $parsed->format('Y-m-d');
                    $time = $parsed->format('H:i') !== '00:00' ? $parsed->format('H:i') . ' WIB' : '';
                    $fullDateTime = $a->tanggal;
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // Fallback ke created_at jika date belum terisi
            if (!$date && $a->created_at) {
                $dt = $a->created_at->timezone('Asia/Jakarta');
                $date = $dt->format('Y-m-d');
                $time = $dt->format('H:i') . ' WIB';
                $fullDateTime = $dt->locale('id')->translatedFormat('d F Y, H:i') . ' WIB';
            }

            // Gambar thumbnail helper
            $imgSrc = null;
            if ($a->gambar) {
                $imgSrc = (str_starts_with($a->gambar, 'http://') || str_starts_with($a->gambar, 'https://'))
                    ? $a->gambar
                    : asset('storage/' . $a->gambar);
            }

            return [
                'id'            => $a->id,
                'judul'         => $a->judul,
                'slug'          => $a->slug,
                'kategori'      => $a->kategori ?: 'Umum',
                'gambar'        => $imgSrc,
                'status'        => $status,
                'status_label'  => match($status) {
                    'scheduled' => 'Terjadwal',
                    'draft'     => 'Draft',
                    default     => 'Published',
                },
                'date'          => $date,
                'time'          => $time,
                'full_datetime' => $fullDateTime,
                'is_active'     => (bool) $a->is_active,
                'edit_url'      => route('admin.articles.edit', $a->id),
                'view_url'      => url('/wawasan/' . $a->slug),
            ];
        })->values();
    }

    /**
     * AJAX endpoint: Resolusi dan validasi URL banner gambar (Google Images, Pinterest, atau link langsung).
     */
    public function resolveBannerUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:2048',
        ], [
            'url.required' => 'URL gambar wajib diisi.',
            'url.max'      => 'Panjang URL melebihi batas 2048 karakter.',
        ]);

        $result = ArticleImageService::resolveUrl($request->url);
        $statusCode = ($result['success'] ?? false) ? 200 : 422;

        return response()->json($result, $statusCode);
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'slug'         => 'required|string|unique:articles,slug|max:255',
            'kategori'     => 'nullable|string|max:100',
            'source_name'  => 'nullable|string|max:255',
            'source_url'   => 'nullable|url|max:1000',
            'image_type'   => 'nullable|in:upload,url',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gambar_url'   => 'nullable|url|max:2048',
            'ringkasan'    => 'nullable|string',
            'isi_artikel'  => 'nullable|string',
            'status'       => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ], [
            'judul.required'      => 'Judul artikel wajib diisi.',
            'slug.required'       => 'Slug URL artikel wajib diisi.',
            'slug.unique'         => 'Slug sudah digunakan oleh artikel lain.',
            'source_url.url'      => 'Format URL sumber tidak valid.',
            'gambar.image'        => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'        => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max'          => 'Ukuran gambar maksimal 5MB.',
            'gambar_url.url'      => 'Format URL gambar eksternal tidak valid.',
            'status.in'           => 'Status artikel tidak valid.',
        ]);

        if ($request->status === 'scheduled' && !$request->filled('published_at')) {
            return back()->withInput()->withErrors([
                'published_at' => 'Waktu publikasi terjadwal wajib diisi saat memilih status Jadwalkan.'
            ]);
        }

        $data = $request->only([
            'judul',
            'slug',
            'kategori',
            'source_name',
            'source_url',
            'ringkasan',
            'isi_artikel',
            'status',
        ]);

        // Penanganan Gambar (Upload vs URL)
        $imageType = $request->input('image_type', 'upload');
        if ($imageType === 'url' && $request->filled('gambar_url')) {
            $inputUrl = trim($request->input('gambar_url'));
            $resolved = ArticleImageService::resolveUrl($inputUrl);

            if (!$resolved['success'] || empty($resolved['image_url'])) {
                return back()->withInput()->withErrors([
                    'gambar_url' => $resolved['message'] ?? 'URL gambar tidak valid atau tidak dapat diakses.'
                ]);
            }

            $finalImageUrl = $resolved['image_url'];

            // Jika admin memilih untuk menyimpan salinan ke server lokal (via centang checkbox)
            if ($request->boolean('simpan_ke_server', false)) {
                $downloadResult = ArticleImageService::downloadAndStore($finalImageUrl);
                if (!$downloadResult['success']) {
                    return back()->withInput()->withErrors([
                        'gambar_url' => 'Gagal mengunduh gambar ke server lokal: ' . ($downloadResult['error'] ?? 'Gambar tidak dapat diakses atau bukan format gambar valid.')
                    ]);
                }
                $data['gambar'] = $downloadResult['path'];
            } else {
                $data['gambar'] = $finalImageUrl;
            }
        } elseif ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('articles', 'public');
        } else {
            $data['gambar'] = null;
        }

        // Penanganan Status & Tanggal Publikasi (WIB)
        try {
            if ($request->status === 'published') {
                $pubDate = $request->filled('published_at')
                    ? Carbon::parse($request->published_at, 'Asia/Jakarta')
                    : now('Asia/Jakarta');
                $data['published_at'] = $pubDate;
                $data['tanggal']      = $pubDate->locale('id')->translatedFormat('d F Y');
            } elseif ($request->status === 'scheduled') {
                $pubDate = Carbon::parse($request->published_at, 'Asia/Jakarta');
                $data['published_at'] = $pubDate;
                $data['tanggal']      = $pubDate->locale('id')->translatedFormat('d F Y');
            } else { // draft
                $data['published_at'] = null;
                $data['tanggal']      = null;
            }
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'published_at' => 'Format tanggal dan waktu publikasi tidak valid.'
            ]);
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
            'judul'        => 'required|string|max:255',
            'slug'         => 'required|string|unique:articles,slug,' . $article->id . '|max:255',
            'kategori'     => 'nullable|string|max:100',
            'source_name'  => 'nullable|string|max:255',
            'source_url'   => 'nullable|url|max:1000',
            'image_type'   => 'nullable|in:upload,url,current',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gambar_url'   => 'nullable|url|max:2048',
            'ringkasan'    => 'nullable|string',
            'isi_artikel'  => 'nullable|string',
            'status'       => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ], [
            'judul.required'      => 'Judul artikel wajib diisi.',
            'slug.required'       => 'Slug URL artikel wajib diisi.',
            'slug.unique'         => 'Slug sudah digunakan oleh artikel lain.',
            'source_url.url'      => 'Format URL sumber tidak valid.',
            'gambar.image'        => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'        => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max'          => 'Ukuran gambar maksimal 5MB.',
            'gambar_url.url'      => 'Format URL gambar eksternal tidak valid.',
            'status.in'           => 'Status artikel tidak valid.',
        ]);

        if ($request->status === 'scheduled' && !$request->filled('published_at')) {
            return back()->withInput()->withErrors([
                'published_at' => 'Waktu publikasi terjadwal wajib diisi saat memilih status Jadwalkan.'
            ]);
        }

        $data = $request->only([
            'judul',
            'slug',
            'kategori',
            'source_name',
            'source_url',
            'ringkasan',
            'isi_artikel',
            'status',
        ]);

        // Penanganan Gambar (Pertahankan gambar lama jika tidak diganti)
        $imageType = $request->input('image_type');
        if ($imageType === 'url' && $request->filled('gambar_url')) {
            $inputUrl = trim($request->input('gambar_url'));
            $resolved = ArticleImageService::resolveUrl($inputUrl);

            if (!$resolved['success'] || empty($resolved['image_url'])) {
                return back()->withInput()->withErrors([
                    'gambar_url' => $resolved['message'] ?? 'URL gambar tidak valid atau tidak dapat diakses.'
                ]);
            }

            $finalImageUrl = $resolved['image_url'];

            // Jika memilih menyimpan ke server lokal (via centang checkbox)
            if ($request->boolean('simpan_ke_server', false)) {
                $downloadResult = ArticleImageService::downloadAndStore($finalImageUrl);
                if (!$downloadResult['success']) {
                    return back()->withInput()->withErrors([
                        'gambar_url' => 'Gagal mengunduh gambar ke server lokal: ' . ($downloadResult['error'] ?? 'Gambar tidak dapat diakses atau bukan format gambar valid.')
                    ]);
                }

                if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
                    Storage::disk('public')->delete($article->gambar);
                }
                $data['gambar'] = $downloadResult['path'];
            } else {
                if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
                    Storage::disk('public')->delete($article->gambar);
                }
                $data['gambar'] = $finalImageUrl;
            }
        } elseif ($request->hasFile('gambar')) {
            // Hapus file fisik lokal lama
            if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
                Storage::disk('public')->delete($article->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('articles', 'public');
        } else {
            // Pertahankan gambar lama
            $data['gambar'] = $article->gambar;
        }

        // Penanganan Status & Tanggal Publikasi (WIB)
        try {
            if ($request->status === 'published') {
                $pubDate = $request->filled('published_at')
                    ? Carbon::parse($request->published_at, 'Asia/Jakarta')
                    : ($article->published_at ?? now('Asia/Jakarta'));
                $data['published_at'] = $pubDate;
                $data['tanggal']      = $pubDate->locale('id')->translatedFormat('d F Y');
            } elseif ($request->status === 'scheduled') {
                $pubDate = Carbon::parse($request->published_at, 'Asia/Jakarta');
                $data['published_at'] = $pubDate;
                $data['tanggal']      = $pubDate->locale('id')->translatedFormat('d F Y');
            } else { // draft
                $data['published_at'] = null;
                $data['tanggal']      = null;
            }
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'published_at' => 'Format tanggal dan waktu publikasi tidak valid.'
            ]);
        }

        $data['is_active'] = $request->has('is_active');

        // created_at TIDAK ditimpa ketika artikel diedit (Eloquent mengelola created_at otomatis)
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
