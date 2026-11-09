<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'nomor',
        'nama_layanan',
        'slug',
        'image',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'icon',
        'is_active',
    ];

    /**
     * URL gambar layanan (prioritas: uploaded image > file gambar slug bawaan > placeholder fallback).
     */
    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (Str::startsWith($this->image, ['http://', 'https://', '/'])) {
                return $this->image;
            }
            return asset('storage/' . ltrim($this->image, '/'));
        }

        if (!empty($this->slug) && file_exists(public_path('images/services/' . $this->slug . '.jpg'))) {
            return asset('images/services/' . $this->slug . '.jpg');
        }

        return asset('images/ai-hero-bg.jpg');
    }

    /**
     * Memeriksa apakah layanan memiliki gambar custom yang diunggah.
     */
    public function getHasUploadedImageAttribute(): bool
    {
        return !empty($this->image);
    }

    /**
     * Daftar preset ikon standar SVG (Lucide / Feather) untuk modul Layanan AI.
     */
    public static function availableIcons(): array
    {
        return [
            [
                'id'       => 'ai-chatbot',
                'name'     => 'AI Chatbot / Asisten',
                'category' => 'Percakapan',
                'path'     => 'M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z',
            ],
            [
                'id'       => 'ai-knowledge-base',
                'name'     => 'Knowledge Base / RAG',
                'category' => 'Dokumen',
                'path'     => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5a2.5 2.5 0 0 0-2.5 2.5v15Z',
            ],
            [
                'id'       => 'ai-agent',
                'name'     => 'AI Agent / Otonom',
                'category' => 'Alur Kerja',
                'path'     => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6',
            ],
            [
                'id'       => 'ai-automation',
                'name'     => 'AI Automation',
                'category' => 'Otomatisasi',
                'path'     => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
            ],
            [
                'id'       => 'generative-ai',
                'name'     => 'Generative AI',
                'category' => 'Kreatif',
                'path'     => 'M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83',
            ],
            [
                'id'       => 'ai-voice-audio',
                'name'     => 'AI Voice & Audio',
                'category' => 'Suara',
                'path'     => 'M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z M19 10v2a7 7 0 0 1-14 0v-2 M12 19v4 M8 23h8',
            ],
            [
                'id'       => 'computer-vision',
                'name'     => 'Computer Vision AI',
                'category' => 'Visual',
                'path'     => 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
            ],
            [
                'id'       => 'predictive-ai',
                'name'     => 'Predictive AI',
                'category' => 'Analisis',
                'path'     => 'M3 3v18h18 M18 9l-5 5-4-4-5 5',
            ],
            [
                'id'       => 'recommendation-ai',
                'name'     => 'Recommendation AI',
                'category' => 'E-Commerce',
                'path'     => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
            ],
            [
                'id'       => 'ai-analytics',
                'name'     => 'AI Analytics / BI',
                'category' => 'Data & BI',
                'path'     => 'M21.21 15.89A10 10 0 1 1 8 2.83 M22 12A10 10 0 0 0 12 2v10z',
            ],
            [
                'id'       => 'ai-marketing-sales',
                'name'     => 'AI Marketing & Sales',
                'category' => 'Pemasaran',
                'path'     => 'M12 20V10 M18 20V4 M6 20v-4',
            ],
            [
                'id'       => 'ai-customer-service',
                'name'     => 'AI Customer Service',
                'category' => 'Pelanggan',
                'path'     => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
            ],
            [
                'id'       => 'ai-pendidikan',
                'name'     => 'AI Pendidikan / Tutor',
                'category' => 'Edukasi',
                'path'     => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20 M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5a2.5 2.5 0 0 0-2.5 2.5v15Z',
            ],
            [
                'id'       => 'ai-coding',
                'name'     => 'AI Coding / Software',
                'category' => 'Pengembangan',
                'path'     => 'M16 18l6-6-6-6 M8 6l-6 6 6 6',
            ],
            [
                'id'       => 'vertical-ai',
                'name'     => 'Vertical AI / Spesifik',
                'category' => 'Industri',
                'path'     => 'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.27 6.96L12 12.01l8.73-5.05 M12 22.08V12',
            ],
            [
                'id'       => 'ai-saas',
                'name'     => 'AI SaaS / Cloud',
                'category' => 'Platform',
                'path'     => 'M22 12h-4l-3 9L9 3l-3 9H2',
            ],
            [
                'id'       => 'chip',
                'name'     => 'Processor / Hardware AI',
                'category' => 'Infrastruktur',
                'path'     => 'M4 4h16v16H4z M9 2v2 M15 2v2 M9 20v2 M15 20v2 M20 9h2 M20 15h2 M2 9h2 M2 15h2 M9 9h6v6H9z',
            ],
            [
                'id'       => 'database',
                'name'     => 'Database / Big Data',
                'category' => 'Infrastruktur',
                'path'     => 'M21 5c0 1.66-4 3-9 3s-9-1.34-9-3 4-3 9-3 9 1.34 9 3z M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5 M3 12c0 1.66 4 3 9 3s9-1.34 9-3',
            ],
            [
                'id'       => 'cloud',
                'name'     => 'Cloud Network',
                'category' => 'Infrastruktur',
                'path'     => 'M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z',
            ],
            [
                'id'       => 'layers',
                'name'     => 'Neural Layers',
                'category' => 'Machine Learning',
                'path'     => 'M12 2L2 7l10 5 10-5-10-5z M2 17l10 5 10-5 M2 12l10 5 10-5',
            ],
            [
                'id'       => 'globe',
                'name'     => 'Jaringan Global',
                'category' => 'Konektivitas',
                'path'     => 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z M2 12h20 M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z',
            ],
            [
                'id'       => 'zap',
                'name'     => 'High Speed / Real-Time',
                'category' => 'Performa',
                'path'     => 'M13 2L3 14h9l-1 8 10-12h-9l1-8z',
            ],
            [
                'id'       => 'settings',
                'name'     => 'Integrasi & Konfigurasi',
                'category' => 'Pengaturan',
                'path'     => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z',
            ],
            [
                'id'       => 'search',
                'name'     => 'Smart Search / Penemuan',
                'category' => 'Pencarian',
                'path'     => 'M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z M21 21l-4.35-4.35',
            ],
        ];
    }
}
