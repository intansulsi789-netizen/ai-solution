<?php
$file = 'c:/xampp82/htdocs/webbbb/ai-solution/routes/web.php';
$content = file_get_contents($file);

$solutionsData = [
    'ai-chatbot' => [
        ['title' => 'Integrasi Multikanal', 'desc' => 'Terhubung langsung ke platform populer seperti WhatsApp, web, dan media sosial tanpa hambatan.'],
        ['title' => 'Respon Natural', 'desc' => 'Menggunakan pemrosesan bahasa alami terkini untuk menghasilkan percakapan yang sangat luwes dan manusiawi.'],
        ['title' => 'Serah Terima Agen', 'desc' => 'Mampu mendeteksi secara otomatis dan mengalihkan pertanyaan kompleks ke agen manusia yang tepat.'],
        ['title' => 'Analisis Percakapan', 'desc' => 'Merekam, merangkum, dan menganalisis pola pertanyaan pelanggan secara real-time untuk wawasan bisnis.']
    ],
    'ai-knowledge-base' => [
        ['title' => 'Pencarian Semantik', 'desc' => 'Menemukan dokumen relevan berdasarkan makna konteks, bukan sekadar pencocokan kata kunci.'],
        ['title' => 'Sinkronisasi Data', 'desc' => 'Terhubung dan memperbarui data secara otomatis dari Google Drive, Notion, dan server internal perusahaan.'],
        ['title' => 'Keamanan Terjamin', 'desc' => 'Mengatur hak akses secara granular untuk setiap pengguna terhadap dokumen yang bersifat rahasia.'],
        ['title' => 'Ringkasan Otomatis', 'desc' => 'Menyajikan intisari dan poin-poin penting dari ratusan halaman dokumen panjang secara instan.']
    ],
    'ai-agent' => [
        ['title' => 'Eksekusi Mandiri', 'desc' => 'Menjalankan alur tugas berlapis dan rumit secara berkelanjutan tanpa membutuhkan pengawasan terus-menerus.'],
        ['title' => 'Koneksi API Eksternal', 'desc' => 'Mudah diintegrasikan dengan ratusan aplikasi pihak ketiga untuk menjembatani operasional lintas platform.'],
        ['title' => 'Pemecahan Masalah', 'desc' => 'Mampu beradaptasi dan merencanakan langkah-langkah logis alternatif saat menghadapi error di tengah proses.'],
        ['title' => 'Laporan Aktivitas', 'desc' => 'Mencatat setiap tindakan yang diambil agen secara terperinci untuk transparansi dan proses audit.']
    ],
    'ai-automation' => [
        ['title' => 'Alur Kerja Kustom', 'desc' => 'Mendesain logika otomatisasi yang sepenuhnya disesuaikan dengan standar prosedur operasional unik Anda.'],
        ['title' => 'Ekstraksi Dokumen', 'desc' => 'Membaca, mengenali, dan memindahkan titik-titik data dari faktur atau KTP secara otomatis.'],
        ['title' => 'Notifikasi Pintar', 'desc' => 'Mengirimkan peringatan berjenjang hanya saat sistem mendeteksi anomali atau tugas yang sangat penting.'],
        ['title' => 'Pemrosesan Massal', 'desc' => 'Mampu menangani ribuan baris data atau file besar secara bersamaan hanya dalam hitungan menit.']
    ],
    'generative-ai' => [
        ['title' => 'Pembuatan Artikel', 'desc' => 'Menulis kerangka hingga draft utuh konten blog atau materi marketing secara otomatis dengan SEO.'],
        ['title' => 'Desain Visual', 'desc' => 'Menghasilkan gambar ilustrasi dan aset desain profesional hanya berbekal instruksi deskripsi teks.'],
        ['title' => 'Personalisasi Pesan', 'desc' => 'Menyesuaikan nada dan gaya bahasa penulisan untuk menarik berbagai segmen audiens yang berbeda.'],
        ['title' => 'Ideasi Kreatif', 'desc' => 'Menjadi rekan diskusi yang mampu mempercepat proses brainstorming untuk konsep kampanye atau produk baru.']
    ],
    'ai-voice-audio' => [
        ['title' => 'Text-to-Speech', 'desc' => 'Mengubah naskah tertulis menjadi suara dengan intonasi manusiawi yang terdengar sangat natural.'],
        ['title' => 'Notulen Otomatis', 'desc' => 'Merekam dan mentranskripsi jalannya rapat beserta pelabelan siapa yang berbicara dengan akurasi tinggi.'],
        ['title' => 'Voicebot Interaktif', 'desc' => 'Membangun asisten suara interaktif untuk menjawab panggilan telepon pelanggan tanpa menu angka lawas.'],
        ['title' => 'Analisis Sentimen', 'desc' => 'Mampu mendeteksi emosi, nada bicara, dan tingkat kepuasan pelanggan saat melakukan panggilan telepon.']
    ],
    'computer-vision' => [
        ['title' => 'Deteksi Objek', 'desc' => 'Mengenali barang, komponen spesifik, atau barcode di jalur produksi manufaktur secara akurat.'],
        ['title' => 'Pengenalan Wajah', 'desc' => 'Membangun sistem autentikasi biometrik modern untuk keamanan akses gedung dan rekam absensi.'],
        ['title' => 'Pemantauan Area', 'desc' => 'Menganalisa aliran rekaman CCTV tanpa henti untuk mendeteksi pergerakan atau aktivitas mencurigakan.'],
        ['title' => 'Quality Control', 'desc' => 'Memeriksa tingkat kecacatan produk secara visual dengan kecepatan tinggi melebihi standar manusia.']
    ],
    'predictive-ai' => [
        ['title' => 'Prediksi Permintaan', 'desc' => 'Memperkirakan lonjakan pemesanan produk dan kebutuhan stok berdasarkan analisis tren historis yang kompleks.'],
        ['title' => 'Manajemen Risiko', 'desc' => 'Menghitung secara presisi peluang kegagalan bayar atau potensi kerugian finansial di masa depan.'],
        ['title' => 'Pemeliharaan Mesin', 'desc' => 'Memberi peringatan dini berbulan-bulan sebelum peralatan atau mesin pabrik mengalami kerusakan fatal.'],
        ['title' => 'Optimalisasi Harga', 'desc' => 'Menyesuaikan harga produk secara dinamis seketika berdasarkan rasio permintaan dan pergerakan pasar.']
    ],
    'recommendation-ai' => [
        ['title' => 'Personalisasi Produk', 'desc' => 'Menampilkan daftar barang yang terbukti paling relevan dengan riwayat interaksi dan klik pengguna.'],
        ['title' => 'Penjualan Silang', 'desc' => 'Menyarankan paket produk pelengkap (cross-sell) secara cerdas tepat saat pelanggan menuju proses checkout.'],
        ['title' => 'Konten Dinamis', 'desc' => 'Mengubah seluruh layout dan banner beranda menyesuaikan profil minat unik setiap pengunjung yang datang.'],
        ['title' => 'Retensi Pelanggan', 'desc' => 'Mengirimkan rekomendasi sangat spesifik via email otomatis untuk menarik kembali perhatian pengguna yang pasif.']
    ],
    'ai-analytics' => [
        ['title' => 'Dashboard Analitik', 'desc' => 'Visualisasi data canggih yang membantu Anda melihat keseluruhan kondisi bisnis dengan sangat mudah dan interaktif.'],
        ['title' => 'Laporan Otomatis', 'desc' => 'Membantu menyusun dan menyajikan rutinitas pelaporan berdasarkan seluruh sumber data yang tersedia di perusahaan.'],
        ['title' => 'Analisis Data', 'desc' => 'Membantu membersihkan dan mengolah jutaan baris data mentah untuk menemukan pola dan informasi yang relevan.'],
        ['title' => 'Insight Bisnis', 'desc' => 'Membantu menghasilkan insight berharga langsung dari data sebagai landasan terukur dalam mengambil keputusan bisnis.']
    ],
    'ai-marketing-sales' => [
        ['title' => 'Penilaian Prospek', 'desc' => 'Mengurutkan ribuan calon pelanggan potensial (lead scoring) berdasarkan kemungkinan konversi transaksi tertinggi.'],
        ['title' => 'Pembuatan Kampanye', 'desc' => 'Membantu merancang struktur penawaran, variasi judul, dan materi iklan digital secara instan dan menarik.'],
        ['title' => 'Optimasi Anggaran', 'desc' => 'Mengalokasikan dana iklan secara otomatis dan real-time ke saluran pemasaran dengan tingkat ROI terbaik.'],
        ['title' => 'Analisis Kompetitor', 'desc' => 'Memantau perubahan harga, pergerakan tren pasar, dan strategi promosi para pesaing industri secara terus-menerus.']
    ],
    'ai-customer-service' => [
        ['title' => 'Tiket Otomatis', 'desc' => 'Membaca, mengkategorikan tingkat urgensi, dan mendistribusikan keluhan ke departemen yang paling tepat.'],
        ['title' => 'Balasan Cerdas', 'desc' => 'Menyarankan draf jawaban lengkap dan akurat di layar agen saat mereka membalas komplain pelanggan.'],
        ['title' => 'Analisis Kepuasan', 'desc' => 'Mengukur kepuasan pelanggan secara konstan tanpa perlu mengirimkan formulir survei manual setiap saat.'],
        ['title' => 'Pemantauan SLA', 'desc' => 'Menjaga kepatuhan performa layanan dengan memastikan keluhan tereskalasi sebelum batas waktu yang dijanjikan.']
    ],
    'ai-pendidikan' => [
        ['title' => 'Jalur Belajar Personal', 'desc' => 'Menyusun silabus pembelajaran yang sangat adaptif, menyesuaikan dengan kemampuan dasar dan kecepatan siswa.'],
        ['title' => 'Penilaian Otomatis', 'desc' => 'Mengkoreksi ribuan ujian pilihan ganda serta memberikan umpan balik mendetail pada tes berbasis esai.'],
        ['title' => 'Asisten Diskusi', 'desc' => 'Mendampingi siswa untuk menjawab pertanyaan akademik mereka 24/7 saat di luar lingkungan sekolah.'],
        ['title' => 'Analisis Perkembangan', 'desc' => 'Memberikan laporan metrik komprehensif kepada guru mengenai titik kekuatan dan hambatan spesifik tiap siswa.']
    ],
    'ai-coding' => [
        ['title' => 'Auto-Complete Kode', 'desc' => 'Memprediksi dan menyarankan blok baris kode selanjutnya untuk secara radikal mempercepat pengetikan program.'],
        ['title' => 'Deteksi Bug Cerdas', 'desc' => 'Menemukan celah keamanan tersembunyi dan potensi kelemahan sistem jauh sebelum aplikasi dirilis ke publik.'],
        ['title' => 'Refactoring Sistem', 'desc' => 'Merapikan dan menulis ulang struktur arsitektur kode lawas agar jauh lebih efisien dan modern tanpa merusak fungsi.'],
        ['title' => 'Dokumentasi Otomatis', 'desc' => 'Membaca source code dan langsung membuat penjelasan dokumentasi serta spesifikasi API untuk sesama developer.']
    ],
    'vertical-ai' => [
        ['title' => 'Pemahaman Domain', 'desc' => 'Sistem khusus yang telah dilatih intensif dengan terminologi medis, rekam hukum, atau prosedur industri spesifik Anda.'],
        ['title' => 'Analisis Dokumen', 'desc' => 'Mampu memeriksa kelengkapan klausul dan menyoroti risiko hukum dalam puluhan halaman tebal dokumen kontrak.'],
        ['title' => 'Kepatuhan Regulasi', 'desc' => 'Secara aktif memantau agar seluruh proses operasional tetap sejalan dan patuh dalam batas aturan ketat industri.'],
        ['title' => 'Integrasi Sistem Spesifik', 'desc' => 'Memiliki fleksibilitas arsitektur tinggi untuk terhubung langsung dengan software ERP tua atau hardware industri tertentu.']
    ],
    'ai-saas' => [
        ['title' => 'Infrastruktur Skalabel', 'desc' => 'Sistem cloud yang dirancang untuk secara otomatis menampung beban ribuan pengguna bersamaan tanpa perlambatan.'],
        ['title' => 'Manajemen Berlangganan', 'desc' => 'Sistem tagihan penagihan otomatis yang memantau tingkat paket, pemakaian kuota AI, dan status pembayaran pelanggan.'],
        ['title' => 'Keamanan Multi-Tenant', 'desc' => 'Arsitektur database yang secara ketat menjamin keamanan dan pemisahan data tiap klien agar tidak pernah tercampur.'],
        ['title' => 'Dashboard Manajemen', 'desc' => 'Menyediakan antarmuka manajemen canggih nan intuitif bagi admin dan pengguna akhir untuk mengontrol profil mereka.']
    ]
];

$lines = explode("\n", $content);
$newLines = [];
$currentSlug = '';

foreach ($lines as $line) {
    if (preg_match("/'slug'\s*=>\s*'([^']+)'/", $line, $matches)) {
        $currentSlug = $matches[1];
    }
    
    $newLines[] = $line;
    
    if (strpos($line, "'benefits' => [") !== false && isset($solutionsData[$currentSlug])) {
        // Insert solutions before benefits
        array_pop($newLines); // remove the benefits line for a moment
        
        $solutionsCode = "        'solutions' => [\n";
        foreach ($solutionsData[$currentSlug] as $sol) {
            $solutionsCode .= "            ['title' => '{$sol['title']}', 'desc' => '{$sol['desc']}'],\n";
        }
        $solutionsCode .= "        ],\n";
        $solutionsCode .= $line; // add benefits back
        
        $newLines[] = rtrim($solutionsCode, "\n");
    }
}

file_put_contents($file, implode("\n", $newLines));
echo "Success\n";
