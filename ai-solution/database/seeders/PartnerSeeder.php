<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        $partners = [
            // Row 1
            ['nama' => 'Pertamina',       'logo' => 'https://logo.clearbit.com/pertamina.com',         'urutan' => 1],
            ['nama' => 'Telkom Indonesia','logo' => 'https://logo.clearbit.com/telkom.co.id',           'urutan' => 2],
            ['nama' => 'Gojek',           'logo' => 'https://logo.clearbit.com/gojek.com',             'urutan' => 3],
            ['nama' => 'Tokopedia',       'logo' => 'https://logo.clearbit.com/tokopedia.com',         'urutan' => 4],
            ['nama' => 'Bank Mandiri',    'logo' => 'https://logo.clearbit.com/bankmandiri.co.id',     'urutan' => 5],
            ['nama' => 'BCA',             'logo' => 'https://logo.clearbit.com/bca.co.id',             'urutan' => 6],
            ['nama' => 'BNI',             'logo' => 'https://logo.clearbit.com/bni.co.id',             'urutan' => 7],
            ['nama' => 'BRI',             'logo' => 'https://logo.clearbit.com/bri.co.id',             'urutan' => 8],
            ['nama' => 'Garuda Indonesia','logo' => 'https://logo.clearbit.com/garuda-indonesia.com',  'urutan' => 9],
            ['nama' => 'Astra',           'logo' => 'https://logo.clearbit.com/astra.co.id',           'urutan' => 10],
            // Row 2
            ['nama' => 'PLN',             'logo' => 'https://logo.clearbit.com/pln.co.id',             'urutan' => 11],
            ['nama' => 'XL Axiata',       'logo' => 'https://logo.clearbit.com/xl.co.id',              'urutan' => 12],
            ['nama' => 'Indosat',         'logo' => 'https://logo.clearbit.com/indosatooredoo.com',    'urutan' => 13],
            ['nama' => 'Shopee',          'logo' => 'https://logo.clearbit.com/shopee.co.id',          'urutan' => 14],
            ['nama' => 'Traveloka',       'logo' => 'https://logo.clearbit.com/traveloka.com',         'urutan' => 15],
            ['nama' => 'Bukalapak',       'logo' => 'https://logo.clearbit.com/bukalapak.com',         'urutan' => 16],
            ['nama' => 'Halodoc',         'logo' => 'https://logo.clearbit.com/halodoc.com',           'urutan' => 17],
            ['nama' => 'Ruangguru',       'logo' => 'https://logo.clearbit.com/ruangguru.com',         'urutan' => 18],
            ['nama' => 'KAI',             'logo' => 'https://logo.clearbit.com/kai.id',                'urutan' => 19],
            ['nama' => 'Antam',           'logo' => 'https://logo.clearbit.com/antam.com',             'urutan' => 20],
        ];

        $now = now();
        foreach ($partners as &$p) {
            $p['is_active']   = true;
            $p['created_at']  = $now;
            $p['updated_at']  = $now;
        }

        DB::table('partners')->insert($partners);
    }
}
