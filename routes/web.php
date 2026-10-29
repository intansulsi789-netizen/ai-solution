<?php

use Illuminate\Support\Facades\Route;

/**
 * Helper untuk mengambil data services dari DB.
 * Dipanggil di dalam closure route agar tidak dieksekusi saat boot/artisan.
 */
$getServices = function () {
    return \App\Models\Service::where('is_active', true)->orderBy('nomor')->get()->map(function ($s) {
        $lengkap = json_decode($s->deskripsi_lengkap, true) ?? ['solutions' => [], 'benefits' => []];
        return [
            'slug'      => $s->slug,
            'title'     => $s->nama_layanan,
            'icon'      => $s->icon,
            'image'     => $s->image,
            'desc'      => $s->deskripsi_singkat,
            'solutions' => $lengkap['solutions'] ?? [],
            'benefits'  => $lengkap['benefits'] ?? [],
        ];
    })->toArray();
};

/**
 * Helper untuk mengambil data articles terbit dari DB (published & published_at <= now()).
 * Dipanggil di dalam closure route agar tidak dieksekusi saat boot/artisan.
 */
$getArticles = function () {
    try {
        return \App\Models\Article::published()
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->get()
            ->map(function ($a) {
                $isi = json_decode($a->isi_artikel, true);
                if (!is_array($isi)) {
                    $isi = [
                        'content' => $a->isi_artikel ?? '',
                        'takeaway' => '',
                        'readTime' => '',
                    ];
                }

                // Gunakan tanggal publikasi WIB jika tersedia
                $displayDate = !empty($a->published_at)
                    ? $a->published_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y')
                    : ($a->tanggal ?: '');

                return [
                    'slug'        => $a->slug,
                    'title'       => $a->judul,
                    'category'    => $a->kategori,
                    'author_name' => $a->author_name ?? null,
                    'date'        => $displayDate,
                    'source_name' => $a->source_name ?? null,
                    'source_url'  => $a->source_url ?? null,
                    'readTime'    => $isi['readTime'] ?? '',
                    'image'       => $a->image_url,
                    'summary'     => $a->ringkasan,
                    'content'     => $isi['content'] ?? '',
                    'takeaway'    => $isi['takeaway'] ?? '',
                ];
            })->toArray();
    } catch (\Throwable $e) {
        // Fallback jika database belum memiliki kolom baru
        try {
            return \App\Models\Article::where('is_active', true)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($a) {
                    $isi = json_decode($a->isi_artikel, true);
                    if (!is_array($isi)) {
                        $isi = [
                            'content' => $a->isi_artikel ?? '',
                            'takeaway' => '',
                            'readTime' => '',
                        ];
                    }
                    return [
                        'slug'        => $a->slug,
                        'title'       => $a->judul,
                        'category'    => $a->kategori,
                        'author_name' => $a->author_name ?? null,
                        'date'        => $a->tanggal ?: '',
                        'source_name' => null,
                        'source_url'  => null,
                        'readTime'    => $isi['readTime'] ?? '',
                        'image'       => $a->image_url,
                        'summary'     => $a->ringkasan,
                        'content'     => $isi['content'] ?? '',
                        'takeaway'    => $isi['takeaway'] ?? '',
                    ];
                })->toArray();
        } catch (\Throwable $fallbackErr) {
            return [];
        }
    }
};

Route::get('/', function () use ($getServices, $getArticles) {
    $services    = $getServices();
    $adminUser   = \App\Models\User::first();
    $partners    = \App\Models\Partner::where('is_active', true)->orderBy('urutan', 'asc')->get();
    $cmsAbout    = \App\Models\CmsAbout::first();
    $cmsHomepage = \App\Models\CmsHomepage::first();
    $cmsContact  = \App\Models\CmsContact::first();

    $articles    = $getArticles();
    // limit to 3 latest articles for homepage
    $homepageArticles = array_slice($articles, 0, 3);

    return view('welcome', compact('services', 'adminUser', 'partners', 'cmsAbout', 'cmsHomepage', 'cmsContact', 'homepageArticles', 'articles'));
});

Route::get('/layanan/{slug}', function ($slug) {
    $s = \App\Models\Service::where('slug', $slug)->where('is_active', true)->first();

    if (!$s) {
        abort(404);
    }

    $lengkap = json_decode($s->deskripsi_lengkap, true);
    if (!is_array($lengkap)) {
        $lengkap = ['solutions' => [], 'benefits' => []];
    }

    $serviceData = [
        'id'        => $s->id,
        'slug'      => $s->slug,
        'title'     => $s->nama_layanan,
        'icon'      => $s->icon,
        'image'     => $s->image,
        'desc'      => $s->deskripsi_singkat,
        'solutions' => $lengkap['solutions'] ?? [],
        'benefits'  => $lengkap['benefits'] ?? [],
    ];

    return view('service-detail', ['service' => $serviceData]);
});

Route::get('/wawasan', function (\Illuminate\Http\Request $request) {
    \App\Models\Article::autoPublishOverdue();

    try {
        $query = \App\Models\Article::published();
    } catch (\Throwable $e) {
        $query = \App\Models\Article::where('is_active', true);
    }

    if ($request->filled('q')) {
        $q = trim($request->q);
        $query->where(function($w) use ($q) {
            $w->where('judul', 'like', "%{$q}%")
              ->orWhere('ringkasan', 'like', "%{$q}%")
              ->orWhere('kategori', 'like', "%{$q}%")
              ->orWhere('isi_artikel', 'like', "%{$q}%")
              ->orWhere('author_name', 'like', "%{$q}%");
        });
    }

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    $articles = $query->orderByRaw('COALESCE(published_at, created_at) DESC')
        ->paginate(6)
        ->withQueryString();

    $cmsContact = \App\Models\CmsContact::first();
    $cmsSeo     = \App\Models\CmsSeo::first();

    return view('wawasan', compact('articles', 'cmsContact', 'cmsSeo'));
})->name('articles.index');

Route::get('/wawasan/{slug}', function ($slug) use ($getArticles) {
    $articles = $getArticles();
    $articleData = collect($articles)->firstWhere('slug', $slug);
    if (!$articleData) {
        abort(404);
    }
    $currCat = $articleData['category'] ?? '';
    $otherArticles = collect($articles)
        ->where('slug', '!=', $slug)
        ->sortByDesc(function ($item) use ($currCat) {
            return ($item['category'] ?? '') === $currCat ? 1 : 0;
        })
        ->values()
        ->all();
    return view('article-detail', ['article' => $articleData, 'allArticles' => $otherArticles]);
})->name('article.detail');


// ============================================================
// FRONTEND ROUTES (POST)
// ============================================================
Route::post('/konsultasi', function (\Illuminate\Http\Request $request) {
    // Rate Limiting: Maksimal 5 pengiriman per menit per IP untuk mencegah spam
    $throttleKey = 'konsultasi:' . ($request->ip() ?: 'unknown');

    try {
        $limiter = new \Illuminate\Cache\RateLimiter(\Illuminate\Support\Facades\Cache::store('file'));
    } catch (\Throwable $e) {
        $limiter = app(\Illuminate\Cache\RateLimiter::class);
    }

    if ($limiter->tooManyAttempts($throttleKey, 5)) {
        $seconds = $limiter->availableIn($throttleKey);
        return redirect('/#konsultasi')
            ->withInput()
            ->withErrors(['rate_limit' => "Terlalu banyak permintaan konsultasi. Mohon tunggu {$seconds} detik sebelum mengirim kembali."]);
    }

    $limiter->hit($throttleKey, 60);

    $validated = $request->validate([
        'nama'      => 'required|string|max:255',
        'email'     => 'required|email|max:255',
        'whatsapp'  => 'nullable|string|max:20',
        'kebutuhan' => 'required|string|max:3000'
    ], [
        'nama.required'      => 'Nama lengkap wajib diisi.',
        'nama.string'        => 'Nama lengkap harus berupa teks yang valid.',
        'nama.max'           => 'Nama lengkap maksimal 255 karakter.',
        'email.required'     => 'Alamat email wajib diisi.',
        'email.email'        => 'Format alamat email tidak valid.',
        'email.max'          => 'Alamat email maksimal 255 karakter.',
        'whatsapp.max'       => 'Nomor WhatsApp maksimal 20 digit.',
        'kebutuhan.required' => 'Kebutuhan AI Anda wajib diisi.',
        'kebutuhan.max'      => 'Deskripsi kebutuhan maksimal 3000 karakter.',
    ]);

    \App\Models\Consultation::create([
        'nama'      => strip_tags(trim($validated['nama'])),
        'email'     => strtolower(trim($validated['email'])),
        'whatsapp'  => !empty($validated['whatsapp']) ? trim($validated['whatsapp']) : null,
        'kebutuhan' => strip_tags(trim($validated['kebutuhan'])),
        'is_read'   => false
    ]);

    return redirect('/#konsultasi')->with('success', 'Pesan konsultasi Anda berhasil dikirim. Kami akan segera menghubungi Anda.');
})->name('konsultasi.store');

Route::get('/sitemap.xml', function () {
    $services = \App\Models\Service::where('is_active', true)->get();
    $articles = \App\Models\Article::published()->get();
    
    return response()->view('sitemap', [
        'services' => $services,
        'articles' => $articles,
    ])->header('Content-Type', 'text/xml');
});

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nDisallow: /admin\nAllow: /\n\nSitemap: https://jogjaaisolutions.my.id/sitemap.xml";
    return response($content)->header('Content-Type', 'text/plain');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Shortcut /admin -> /admin/login
Route::redirect('/admin', '/admin/login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware(['guest', 'throttle:15,1'])->name('login.post');

    // Protected admin pages — accessible by ALL authenticated admin roles
    Route::middleware('auth')->group(function () {

        // ── Shared: Both admin_website & admin_marketing ──
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('articles/calendar', [\App\Http\Controllers\Admin\AdminArticleController::class, 'calendar'])->name('articles.calendar');
        Route::post('articles/resolve-banner-url', [\App\Http\Controllers\Admin\AdminArticleController::class, 'resolveBannerUrl'])->name('articles.resolveBannerUrl');
        Route::resource('articles', \App\Http\Controllers\Admin\AdminArticleController::class);

        Route::get('consultations', [\App\Http\Controllers\Admin\AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::get('consultations/{id}', [\App\Http\Controllers\Admin\AdminConsultationController::class, 'show'])->name('consultations.show');
        Route::put('consultations/{id}/toggle-read', [\App\Http\Controllers\Admin\AdminConsultationController::class, 'toggleRead'])->name('consultations.toggleRead');
        Route::delete('consultations/{id}', [\App\Http\Controllers\Admin\AdminConsultationController::class, 'destroy'])->name('consultations.destroy');

        Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // ── Admin Website only ──
        Route::middleware('role:admin_website')->group(function () {
            Route::resource('services', \App\Http\Controllers\Admin\AdminServiceController::class);
            Route::resource('partners', \App\Http\Controllers\Admin\AdminPartnerController::class);
            Route::post('/partners-reorder', [\App\Http\Controllers\Admin\AdminPartnerController::class, 'reorder'])->name('partners.reorder');

            Route::prefix('cms')->name('cms.')->group(function () {
                Route::get('/homepage', [\App\Http\Controllers\Admin\AdminCmsHomepageController::class, 'index'])->name('homepage.index');
                Route::put('/homepage', [\App\Http\Controllers\Admin\AdminCmsHomepageController::class, 'update'])->name('homepage.update');
                Route::get('/logo', [\App\Http\Controllers\Admin\AdminCmsLogoController::class, 'index'])->name('logo.index');
                Route::put('/logo', [\App\Http\Controllers\Admin\AdminCmsLogoController::class, 'update'])->name('logo.update');
                Route::get('/about', [\App\Http\Controllers\Admin\AdminCmsAboutController::class, 'index'])->name('about.index');
                Route::put('/about', [\App\Http\Controllers\Admin\AdminCmsAboutController::class, 'update'])->name('about.update');
                Route::get('/contact', [\App\Http\Controllers\Admin\AdminCmsContactController::class, 'index'])->name('contact.index');
                Route::put('/contact', [\App\Http\Controllers\Admin\AdminCmsContactController::class, 'update'])->name('contact.update');
            });
        });

        // ── Admin Marketing/SEO only ──
        Route::middleware('role:admin_marketing')->group(function () {
            Route::get('/seo', [\App\Http\Controllers\Admin\AdminSeoController::class, 'index'])->name('seo.index');
            Route::put('/seo', [\App\Http\Controllers\Admin\AdminSeoController::class, 'update'])->name('seo.update');
        });
    });

});

