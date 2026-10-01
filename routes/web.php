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

try {
    $articles = \App\Models\Article::where('is_active', true)->where('status', 'published')->orderBy('created_at', 'desc')->get()->map(function($a) {
        $isi = json_decode($a->isi_artikel, true) ?? [];
        return [
            'slug'     => $a->slug,
            'title'    => $a->judul,
            'category' => $a->kategori,
            'date'     => $a->tanggal,
            'readTime' => $isi['readTime'] ?? '',
            'image'    => $a->gambar ? (str_starts_with($a->gambar, 'http') ? $a->gambar : asset('storage/' . $a->gambar)) : '',
            'summary'  => $a->ringkasan,
            'content'  => $isi['content'] ?? '',
            'takeaway' => $isi['takeaway'] ?? '',
        ];
    })->toArray();
} catch (\Exception $e) {
    $articles = [];
}

Route::get('/', function () use ($getServices, $articles) {
    $services    = $getServices();
    $adminUser   = \App\Models\User::first();
    $partners    = \App\Models\Partner::where('is_active', true)->orderBy('urutan', 'asc')->get();
    $cmsAbout    = \App\Models\CmsAbout::first();
    $cmsHomepage = \App\Models\CmsHomepage::first();
    $cmsContact  = \App\Models\CmsContact::first();

    // limit to 4 latest articles for homepage
    $homepageArticles = array_slice($articles, 0, 4);

    return view('welcome', compact('services', 'adminUser', 'partners', 'cmsAbout', 'cmsHomepage', 'cmsContact', 'homepageArticles'));
});

Route::get('/layanan/{slug}', function ($slug) use ($getServices) {
    $services    = $getServices();
    $serviceData = collect($services)->firstWhere('slug', $slug);

    if (!$serviceData) {
        abort(404);
    }

    return view('service-detail', ['service' => $serviceData]);
});



Route::get('/wawasan/{slug}', function ($slug) use ($articles) {
    $articleData = collect($articles)->firstWhere('slug', $slug);
    if (!$articleData) {
        abort(404);
    }
    $otherArticles = collect($articles)->where('slug', '!=', $slug)->values()->all();
    return view('article-detail', ['article' => $articleData, 'allArticles' => $otherArticles]);
})->name('article.detail');

// ============================================================
// FRONTEND ROUTES (POST)
// ============================================================
Route::post('/konsultasi', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'whatsapp' => 'nullable|string|max:20',
        'kebutuhan' => 'required|string'
    ]);

    \App\Models\Consultation::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'whatsapp' => $request->whatsapp,
        'kebutuhan' => $request->kebutuhan,
        'is_read' => false
    ]);

    return redirect('/#konsultasi')->with('success', 'Pesan konsultasi Anda berhasil dikirim. Kami akan segera menghubungi Anda.');
})->name('konsultasi.store');

Route::get('/sitemap.xml', function () {
    $services = \App\Models\Service::where('is_active', true)->get();
    $articles = \App\Models\Article::where('is_active', true)->where('status', 'published')->get();
    
    return response()->view('sitemap', [
        'services' => $services,
        'articles' => $articles,
    ])->header('Content-Type', 'text/xml');
});

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nDisallow: /admin\nAllow: /\n\nSitemap: " . url('/sitemap.xml');
    return response($content)->header('Content-Type', 'text/plain');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Login (guest only)
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Protected admin pages — accessible by ALL authenticated admin roles
    Route::middleware('auth')->group(function () {

        // ── Shared: Both admin_website & admin_marketing ──
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

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

