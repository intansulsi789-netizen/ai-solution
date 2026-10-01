<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     * Statistik dihitung dari data statis (hardcoded) di routes/web.php.
     * Ketika CRUD sudah dibuat, ganti ini dengan query database.
     */
    public function index()
    {
        $stats = [
            'total_layanan'    => \App\Models\Service::count(),
            'total_artikel'    => \App\Models\Article::count(),
            'total_partner'    => \App\Models\Partner::count(),
            'total_konsultasi' => \App\Models\Consultation::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
