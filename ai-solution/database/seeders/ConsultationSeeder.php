<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consultations = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'whatsapp' => '081234567890',
                'kebutuhan' => 'Saya tertarik untuk implementasi AI Chatbot di perusahaan kami untuk bagian customer service.',
                'is_read' => false,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'whatsapp' => '089876543210',
                'kebutuhan' => 'Apakah AI Solution menyediakan layanan sentiment analysis untuk media sosial?',
                'is_read' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'nama' => 'Reza Pratama',
                'email' => 'reza.p@example.com',
                'whatsapp' => '085611223344',
                'kebutuhan' => 'Mohon info pricelist untuk solusi computer vision untuk mendeteksi defect pada produk manufaktur.',
                'is_read' => false,
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ]
        ];

        \Illuminate\Support\Facades\DB::table('consultations')->insert($consultations);
    }
}
