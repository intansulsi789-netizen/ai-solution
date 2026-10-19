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
            ['nama' => 'Pertamina',       'logo' => 'https://unavatar.io/pertamina.com',               'urutan' => 1],
            ['nama' => 'Telkom Indonesia','logo' => 'https://unavatar.io/telkom.co.id',                 'urutan' => 2],
            ['nama' => 'Gojek',           'logo' => 'https://cdn.simpleicons.org/gojek',               'urutan' => 3],
            ['nama' => 'Tokopedia',       'logo' => 'https://images.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/e5b8438b.svg', 'urutan' => 4],
            ['nama' => 'Bank Mandiri',    'logo' => 'https://unavatar.io/bankmandiri.co.id',           'urutan' => 5],
            ['nama' => 'BCA',             'logo' => 'https://unavatar.io/bca.co.id',                   'urutan' => 6],
            ['nama' => 'BNI',             'logo' => 'https://unavatar.io/bni.co.id',                   'urutan' => 7],
            ['nama' => 'BRI',             'logo' => 'https://unavatar.io/bri.co.id',                   'urutan' => 8],
            ['nama' => 'Garuda Indonesia','logo' => 'https://unavatar.io/garuda-indonesia.com',        'urutan' => 9],
            ['nama' => 'Astra',           'logo' => 'https://unavatar.io/astra.co.id',                 'urutan' => 10],
            // Row 2
            ['nama' => 'PLN',             'logo' => 'https://unavatar.io/pln.co.id',                   'urutan' => 11],
            ['nama' => 'XL Axiata',       'logo' => 'https://icon.horse/icon/xl.co.id',                'urutan' => 12],
            ['nama' => 'Indosat',         'logo' => 'https://unavatar.io/indosatooredoo.com',          'urutan' => 13],
            ['nama' => 'Shopee',          'logo' => 'https://cdn.simpleicons.org/shopee',              'urutan' => 14],
            ['nama' => 'Traveloka',       'logo' => 'https://unavatar.io/traveloka.com',               'urutan' => 15],
            ['nama' => 'Bukalapak',       'logo' => 'https://cdn.simpleicons.org/bukalapak',           'urutan' => 16],
            ['nama' => 'Halodoc',         'logo' => 'https://unavatar.io/halodoc.com',                 'urutan' => 17],
            ['nama' => 'Ruangguru',       'logo' => 'https://unavatar.io/ruangguru.com',               'urutan' => 18],
            ['nama' => 'KAI',             'logo' => 'https://unavatar.io/kai.id',                      'urutan' => 19],
            ['nama' => 'Antam',           'logo' => 'https://unavatar.io/antam.com',                   'urutan' => 20],
        ];

        $now = now();
        foreach ($partners as &$p) {
            $p['is_active']   = true;
            $p['created_at']  = $now;
            $p['updated_at']  = $now;
        }

        if (DB::table('partners')->count() === 0) {
            DB::table('partners')->insert($partners);
        } else {
            foreach ($partners as $p) {
                DB::table('partners')->updateOrInsert(['nama' => $p['nama']], $p);
            }
        }
    }
}
