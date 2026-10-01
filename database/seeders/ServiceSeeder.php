<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'slug' => 'ai-chatbot',
                'title' => 'AI Chatbot / AI Assistant',
                'icon' => 'M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z',
                'desc' => 'Asisten virtual cerdas yang memahami konteks dan memberikan respon natural untuk klien 24/7.',
                'solutions' => [
                    ['title' => 'Integrasi Multikanal', 'desc' => 'Terhubung langsung ke platform populer seperti WhatsApp, web, dan media sosial tanpa hambatan.'],
                    ['title' => 'Respon Natural', 'desc' => 'Menggunakan pemrosesan bahasa alami terkini untuk menghasilkan percakapan yang sangat luwes dan manusiawi.'],
                    ['title' => 'Serah Terima Agen', 'desc' => 'Mampu mendeteksi secara otomatis dan mengalihkan pertanyaan kompleks ke agen manusia yang tepat.'],
                    ['title' => 'Analisis Percakapan', 'desc' => 'Merekam, merangkum, dan menganalisis pola pertanyaan pelanggan secara real-time untuk wawasan bisnis.'],
                ],
                'benefits' => [
                    'Meningkatkan kepuasan pelanggan dengan respon instan 24 jam.',
                    'Memahami bahasa natural dan konteks untuk percakapan yang lebih manusiawi.',
                    'Terintegrasi dengan sistem CRM untuk eskalasi masalah otomatis.'
                ]
            ],
            [
                'slug' => 'ai-knowledge-base',
                'title' => 'AI Knowledge Base / RAG',
                'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5a2.5 2.5 0 0 0-2.5 2.5v15Z',
                'desc' => 'Sistem pencarian informasi cerdas (Retrieval-Augmented Generation) dari dokumen internal Anda.',
                'solutions' => [
                    ['title' => 'Pencarian Semantik', 'desc' => 'Menemukan dokumen relevan berdasarkan makna konteks, bukan sekadar pencocokan kata kunci.'],
                    ['title' => 'Sinkronisasi Data', 'desc' => 'Terhubung dan memperbarui data secara otomatis dari Google Drive, Notion, dan server internal perusahaan.'],
                    ['title' => 'Keamanan Terjamin', 'desc' => 'Mengatur hak akses secara granular untuk setiap pengguna terhadap dokumen yang bersifat rahasia.'],
                    ['title' => 'Ringkasan Otomatis', 'desc' => 'Menyajikan intisari dan poin-poin penting dari ratusan halaman dokumen panjang secara instan.'],
                ],
                'benefits' => [
                    'Mencari data dari ribuan dokumen internal dalam hitungan detik.',
                    'Memberikan jawaban berdasarkan data perusahaan, bebas dari halusinasi AI.',
                    'Meningkatkan produktivitas tim internal dalam riset informasi.'
                ]
            ],
            [
                'slug' => 'ai-agent',
                'title' => 'AI Agent',
                'icon' => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6',
                'desc' => 'Agen otonom yang dapat mengambil tindakan, menjalankan tugas kompleks, dan berinteraksi dengan API.',
                'solutions' => [
                    ['title' => 'Eksekusi Mandiri', 'desc' => 'Menjalankan alur tugas berlapis dan rumit secara berkelanjutan tanpa membutuhkan pengawasan terus-menerus.'],
                    ['title' => 'Koneksi API Eksternal', 'desc' => 'Mudah diintegrasikan dengan ratusan aplikasi pihak ketiga untuk menjembatani operasional lintas platform.'],
                    ['title' => 'Pemecahan Masalah', 'desc' => 'Mampu beradaptasi dan merencanakan langkah-langkah logis alternatif saat menghadapi error di tengah proses.'],
                    ['title' => 'Laporan Aktivitas', 'desc' => 'Mencatat setiap tindakan yang diambil agen secara terperinci untuk transparansi dan proses audit.'],
                ],
                'benefits' => [
                    'Mampu membuat rencana eksekusi dan mengambil tindakan secara mandiri.',
                    'Terhubung ke puluhan API eksternal untuk menyelesaikan tugas antar aplikasi.',
                    'Beradaptasi dengan perubahan instruksi tanpa perlu programming ulang.'
                ]
            ],
            [
                'slug' => 'ai-automation',
                'title' => 'AI Automation',
                'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
                'desc' => 'Otomatisasi alur kerja berulang dengan cerdas untuk meningkatkan efisiensi operasional tim.',
                'solutions' => [
                    ['title' => 'Alur Kerja Kustom', 'desc' => 'Mendesain logika otomatisasi yang sepenuhnya disesuaikan dengan standar prosedur operasional unik Anda.'],
                    ['title' => 'Ekstraksi Dokumen', 'desc' => 'Membaca, mengenali, dan memindahkan titik-titik data dari faktur atau KTP secara otomatis.'],
                    ['title' => 'Notifikasi Pintar', 'desc' => 'Mengirimkan peringatan berjenjang hanya saat sistem mendeteksi anomali atau tugas yang sangat penting.'],
                    ['title' => 'Pemrosesan Massal', 'desc' => 'Mampu menangani ribuan baris data atau file besar secara bersamaan hanya dalam hitungan menit.'],
                ],
                'benefits' => [
                    'Mengotomatisasi tugas administratif dan pemrosesan data berulang.',
                    'Mampu mengambil keputusan dasar berdasarkan logika bisnis yang diajarkan.',
                    'Mempercepat proses persetujuan dan alur dokumen antar departemen.'
                ]
            ],
            [
                'slug' => 'generative-ai',
                'title' => 'Generative AI (Teks/Gambar/Video)',
                'icon' => 'M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83',
                'desc' => 'Pembuatan konten kreatif dan profesional berskala besar dengan model generatif terkini.',
                'solutions' => [
                    ['title' => 'Pembuatan Artikel', 'desc' => 'Menulis kerangka hingga draft utuh konten blog atau materi marketing secara otomatis dengan SEO.'],
                    ['title' => 'Desain Visual', 'desc' => 'Menghasilkan gambar ilustrasi dan aset desain profesional hanya berbekal instruksi deskripsi teks.'],
                    ['title' => 'Personalisasi Pesan', 'desc' => 'Menyesuaikan nada dan gaya bahasa penulisan untuk menarik berbagai segmen audiens yang berbeda.'],
                    ['title' => 'Ideasi Kreatif', 'desc' => 'Menjadi rekan diskusi yang mampu mempercepat proses brainstorming untuk konsep kampanye atau produk baru.'],
                ],
                'benefits' => [
                    'Menghasilkan artikel, laporan, dan copywriting dengan gaya bahasa yang disesuaikan.',
                    'Menciptakan gambar atau video promosi berkualitas tinggi secara instan.',
                    'Melakukan personalisasi massal konten marketing untuk setiap segmen pelanggan.'
                ]
            ],
            [
                'slug' => 'ai-voice-audio',
                'title' => 'AI Voice & Audio',
                'icon' => 'M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z M19 10v2a7 7 0 0 1-14 0v-2 M12 19v4 M8 23h8',
                'desc' => 'Sintesis suara, transkripsi audio cerdas, dan voicebot interaktif layaknya manusia.',
                'solutions' => [
                    ['title' => 'Text-to-Speech', 'desc' => 'Mengubah naskah tertulis menjadi suara dengan intonasi manusiawi yang terdengar sangat natural.'],
                    ['title' => 'Notulen Otomatis', 'desc' => 'Merekam dan mentranskripsi jalannya rapat beserta pelabelan siapa yang berbicara dengan akurasi tinggi.'],
                    ['title' => 'Voicebot Interaktif', 'desc' => 'Membangun asisten suara interaktif untuk menjawab panggilan telepon pelanggan tanpa menu angka lawas.'],
                    ['title' => 'Analisis Sentimen', 'desc' => 'Mampu mendeteksi emosi, nada bicara, dan tingkat kepuasan pelanggan saat melakukan panggilan telepon.'],
                ],
                'benefits' => [
                    'Mengubah teks menjadi suara (TTS) natural dengan emosi layaknya penyiar.',
                    'Merekam dan mentranskripsi hasil meeting secara otomatis dengan akurasi tinggi.',
                    'Membangun sistem interaktif telepon (IVR) cerdas tanpa menu tekan angka.'
                ]
            ],
            [
                'slug' => 'computer-vision',
                'title' => 'Computer Vision AI',
                'icon' => 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
                'desc' => 'Analisis gambar dan video secara real-time untuk deteksi objek, wajah, atau anomali visual.',
                'solutions' => [
                    ['title' => 'Deteksi Objek', 'desc' => 'Mengenali barang, komponen spesifik, atau barcode di jalur produksi manufaktur secara akurat.'],
                    ['title' => 'Pengenalan Wajah', 'desc' => 'Membangun sistem autentikasi biometrik modern untuk keamanan akses gedung dan rekam absensi.'],
                    ['title' => 'Pemantauan Area', 'desc' => 'Menganalisa aliran rekaman CCTV tanpa henti untuk mendeteksi pergerakan atau aktivitas mencurigakan.'],
                    ['title' => 'Quality Control', 'desc' => 'Memeriksa tingkat kecacatan produk secara visual dengan kecepatan tinggi melebihi standar manusia.'],
                ],
                'benefits' => [
                    'Pemantauan otomatis untuk keamanan dan deteksi pelanggaran di area pabrik.',
                    'Pengecekan kualitas produk (Quality Control) visual secara cepat di jalur produksi.',
                    'Identifikasi identitas dan autentikasi wajah berkecepatan tinggi.'
                ]
            ],
            [
                'slug' => 'predictive-ai',
                'title' => 'Predictive AI',
                'icon' => 'M3 3v18h18 M18 9l-5 5-4-4-5 5',
                'desc' => 'Prakiraan tren, risiko, dan hasil di masa depan berdasarkan analisis pola data historis.',
                'solutions' => [
                    ['title' => 'Prediksi Permintaan', 'desc' => 'Memperkirakan lonjakan pemesanan produk dan kebutuhan stok berdasarkan analisis tren historis yang kompleks.'],
                    ['title' => 'Manajemen Risiko', 'desc' => 'Menghitung secara presisi peluang kegagalan bayar atau potensi kerugian finansial di masa depan.'],
                    ['title' => 'Pemeliharaan Mesin', 'desc' => 'Memberi peringatan dini berbulan-bulan sebelum peralatan atau mesin pabrik mengalami kerusakan fatal.'],
                    ['title' => 'Optimalisasi Harga', 'desc' => 'Menyesuaikan harga produk secara dinamis seketika berdasarkan rasio permintaan dan pergerakan pasar.'],
                ],
                'benefits' => [
                    'Memprediksi pergerakan permintaan pasar (demand forecasting) secara akurat.',
                    'Mendeteksi dini potensi kerusakan mesin pabrik sebelum benar-benar mogok.',
                    'Mencegah risiko gagal bayar (credit scoring) pada perusahaan finansial.'
                ]
            ],
            [
                'slug' => 'recommendation-ai',
                'title' => 'Recommendation AI',
                'icon' => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
                'desc' => 'Sistem rekomendasi terpersonalisasi untuk meningkatkan konversi e-commerce dan engagement.',
                'solutions' => [
                    ['title' => 'Personalisasi Produk', 'desc' => 'Menampilkan daftar barang yang terbukti paling relevan dengan riwayat interaksi dan klik pengguna.'],
                    ['title' => 'Penjualan Silang', 'desc' => 'Menyarankan paket produk pelengkap (cross-sell) secara cerdas tepat saat pelanggan menuju proses checkout.'],
                    ['title' => 'Konten Dinamis', 'desc' => 'Mengubah seluruh layout dan banner beranda menyesuaikan profil minat unik setiap pengunjung yang datang.'],
                    ['title' => 'Retensi Pelanggan', 'desc' => 'Mengirimkan rekomendasi sangat spesifik via email otomatis untuk menarik kembali perhatian pengguna yang pasif.'],
                ],
                'benefits' => [
                    'Menganalisis pola belanja pelanggan untuk menyarankan produk yang relevan.',
                    'Meningkatkan keranjang belanja rata-rata (AOV) dan retensi pelanggan.',
                    'Tampil dinamis menyesuaikan minat pelanggan saat itu juga (real-time intent).'
                ]
            ],
            [
                'slug' => 'ai-analytics',
                'title' => 'AI Analytics / BI',
                'icon' => 'M21.21 15.89A10 10 0 1 1 8 2.83 M22 12A10 10 0 0 0 12 2v10z',
                'desc' => 'Ekstraksi insight mendalam secara otomatis dari big data kompleks untuk keputusan bisnis.',
                'solutions' => [
                    ['title' => 'Dashboard Analitik', 'desc' => 'Visualisasi data canggih yang membantu Anda melihat keseluruhan kondisi bisnis dengan sangat mudah dan interaktif.'],
                    ['title' => 'Laporan Otomatis', 'desc' => 'Membantu menyusun dan menyajikan rutinitas pelaporan berdasarkan seluruh sumber data yang tersedia di perusahaan.'],
                    ['title' => 'Analisis Data', 'desc' => 'Membantu membersihkan dan mengolah jutaan baris data mentah untuk menemukan pola dan informasi yang relevan.'],
                    ['title' => 'Insight Bisnis', 'desc' => 'Membantu menghasilkan insight berharga langsung dari data sebagai landasan terukur dalam mengambil keputusan bisnis.'],
                ],
                'benefits' => [
                    'Menjawab pertanyaan data kompleks cukup dengan mengetik pertanyaan bahasa biasa.',
                    'Mendeteksi anomali pada data keuangan secara otomatis setiap harinya.',
                    'Membuat visualisasi data dan laporan secara mandiri tanpa bantuan data analyst.'
                ]
            ],
            [
                'slug' => 'ai-marketing-sales',
                'title' => 'AI Marketing & Sales',
                'icon' => 'M12 20V10 M18 20V4 M6 20v-4',
                'desc' => 'Optimasi kampanye, personalisasi email, dan kualifikasi leads cerdas berbasis AI.',
                'solutions' => [
                    ['title' => 'Penilaian Prospek', 'desc' => 'Mengurutkan ribuan calon pelanggan potensial (lead scoring) berdasarkan kemungkinan konversi transaksi tertinggi.'],
                    ['title' => 'Pembuatan Kampanye', 'desc' => 'Membantu merancang struktur penawaran, variasi judul, dan materi iklan digital secara instan dan menarik.'],
                    ['title' => 'Optimasi Anggaran', 'desc' => 'Mengalokasikan dana iklan secara otomatis dan real-time ke saluran pemasaran dengan tingkat ROI terbaik.'],
                    ['title' => 'Analisis Kompetitor', 'desc' => 'Memantau perubahan harga, pergerakan tren pasar, dan strategi promosi para pesaing industri secara terus-menerus.'],
                ],
                'benefits' => [
                    'Menulis email promosi personalisasi untuk puluhan ribu kontak dalam semenit.',
                    'Mengkualifikasi calon pelanggan potensial (lead scoring) berdasarkan interaksi.',
                    'Mengoptimalkan pengeluaran anggaran iklan digital agar tidak boncos.'
                ]
            ],
            [
                'slug' => 'ai-customer-service',
                'title' => 'AI Customer Service',
                'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
                'desc' => 'Pusat bantuan omnichannel cerdas yang mampu menyelesaikan keluhan pelanggan instan.',
                'solutions' => [
                    ['title' => 'Tiket Otomatis', 'desc' => 'Membaca, mengkategorikan tingkat urgensi, dan mendistribusikan keluhan ke departemen yang paling tepat.'],
                    ['title' => 'Balasan Cerdas', 'desc' => 'Menyarankan draf jawaban lengkap dan akurat di layar agen saat mereka membalas komplain pelanggan.'],
                    ['title' => 'Analisis Kepuasan', 'desc' => 'Mengukur kepuasan pelanggan secara konstan tanpa perlu mengirimkan formulir survei manual setiap saat.'],
                    ['title' => 'Pemantauan SLA', 'desc' => 'Menjaga kepatuhan performa layanan dengan memastikan keluhan tereskalasi sebelum batas waktu yang dijanjikan.'],
                ],
                'benefits' => [
                    'Menangani lebih dari 80% pertanyaan berulang pelanggan secara instan tanpa agen.',
                    'Menganalisa sentimen pelanggan untuk memberikan prioritas antrian secara otomatis.',
                    'Terhubung dengan WhatsApp, Email, dan Sosmed dalam satu kanal terpusat.'
                ]
            ],
            [
                'slug' => 'ai-pendidikan',
                'title' => 'AI Pendidikan / AI Tutor',
                'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20 M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5a2.5 2.5 0 0 0-2.5 2.5v15Z',
                'desc' => 'Tutor personal cerdas yang menyesuaikan kurikulum berdasarkan kecepatan belajar individu.',
                'solutions' => [
                    ['title' => 'Jalur Belajar Personal', 'desc' => 'Menyusun silabus pembelajaran yang sangat adaptif, menyesuaikan dengan kemampuan dasar dan kecepatan siswa.'],
                    ['title' => 'Penilaian Otomatis', 'desc' => 'Mengkoreksi ribuan ujian pilihan ganda serta memberikan umpan balik mendetail pada tes berbasis esai.'],
                    ['title' => 'Asisten Diskusi', 'desc' => 'Mendampingi siswa untuk menjawab pertanyaan akademik mereka 24/7 saat di luar lingkungan sekolah.'],
                    ['title' => 'Analisis Perkembangan', 'desc' => 'Memberikan laporan metrik komprehensif kepada guru mengenai titik kekuatan dan hambatan spesifik tiap siswa.'],
                ],
                'benefits' => [
                    'Memberikan soal ujian yang disesuaikan dengan titik lemah masing-masing siswa.',
                    'Mengkoreksi tugas esai dan memberikan feedback detail layaknya guru.',
                    'Bekerja 24 jam untuk menjawab kesulitan belajar di luar jam sekolah.'
                ]
            ],
            [
                'slug' => 'ai-coding',
                'title' => 'AI Coding / Software Development',
                'icon' => 'M16 18l6-6-6-6 M8 6l-6 6 6 6',
                'desc' => 'Bantuan pemrograman cerdas, code review otomatis, dan optimasi arsitektur sistem.',
                'solutions' => [
                    ['title' => 'Auto-Complete Kode', 'desc' => 'Memprediksi dan menyarankan blok baris kode selanjutnya untuk secara radikal mempercepat pengetikan program.'],
                    ['title' => 'Deteksi Bug Cerdas', 'desc' => 'Menemukan celah keamanan tersembunyi dan potensi kelemahan sistem jauh sebelum aplikasi dirilis ke publik.'],
                    ['title' => 'Refactoring Sistem', 'desc' => 'Merapikan dan menulis ulang struktur arsitektur kode lawas agar jauh lebih efisien dan modern tanpa merusak fungsi.'],
                    ['title' => 'Dokumentasi Otomatis', 'desc' => 'Membaca source code dan langsung membuat penjelasan dokumentasi serta spesifikasi API untuk sesama developer.'],
                ],
                'benefits' => [
                    'Menghasilkan boilerplate kode utuh hanya dari deskripsi fitur sederhana.',
                    'Membantu mendeteksi celah keamanan dan bug sistem sebelum proses deployment.',
                    'Menulis dokumentasi teknis secara otomatis berdasarkan source code yang ada.'
                ]
            ],
            [
                'slug' => 'vertical-ai',
                'title' => 'Vertical AI',
                'icon' => 'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.27 6.96L12 12.01l8.73-5.05 M12 22.08V12',
                'desc' => 'Solusi AI spesifik domain yang dilatih khusus untuk industri hukum, medis, atau finansial.',
                'solutions' => [
                    ['title' => 'Pemahaman Domain', 'desc' => 'Sistem khusus yang telah dilatih intensif dengan terminologi medis, rekam hukum, atau prosedur industri spesifik Anda.'],
                    ['title' => 'Analisis Dokumen', 'desc' => 'Mampu memeriksa kelengkapan klausul dan menyoroti risiko hukum dalam puluhan halaman tebal dokumen kontrak.'],
                    ['title' => 'Kepatuhan Regulasi', 'desc' => 'Secara aktif memantau agar seluruh proses operasional tetap sejalan dan patuh dalam batas aturan ketat industri.'],
                    ['title' => 'Integrasi Sistem Spesifik', 'desc' => 'Memiliki fleksibilitas arsitektur tinggi untuk terhubung langsung dengan software ERP tua atau hardware industri tertentu.'],
                ],
                'benefits' => [
                    'Memahami istilah-istilah khusus industri yang tidak dimengerti oleh AI umum.',
                    'Bisa menganalisis kontrak legal, dokumen rekam medis, atau laporan aktuaria.',
                    'Mematuhi regulasi ketat industri spesifik terkait privasi data dan compliance.'
                ]
            ],
            [
                'slug' => 'ai-saas',
                'title' => 'AI SaaS',
                'icon' => 'M22 12h-4l-3 9L9 3l-3 9H2',
                'desc' => 'Pengembangan produk SaaS mandiri dengan fitur utama berbasis AI end-to-end terintegrasi.',
                'solutions' => [
                    ['title' => 'Infrastruktur Skalabel', 'desc' => 'Sistem cloud yang dirancang untuk secara otomatis menampung beban ribuan pengguna bersamaan tanpa perlambatan.'],
                    ['title' => 'Manajemen Berlangganan', 'desc' => 'Sistem tagihan penagihan otomatis yang memantau tingkat paket, pemakaian kuota AI, dan status pembayaran pelanggan.'],
                    ['title' => 'Keamanan Multi-Tenant', 'desc' => 'Arsitektur database yang secara ketat menjamin keamanan dan pemisahan data tiap klien agar tidak pernah tercampur.'],
                    ['title' => 'Dashboard Manajemen', 'desc' => 'Menyediakan antarmuka manajemen canggih nan intuitif bagi admin dan pengguna akhir untuk mengontrol profil mereka.'],
                ],
                'benefits' => [
                    'Merancang fondasi platform berlangganan yang siap skala (scalable) untuk global.',
                    'Membangun sistem pembayaran, manajemen user, dan kuota pemakaian AI secara utuh.',
                    'Mengemas kapabilitas model AI canggih ke dalam antarmuka yang sangat ramah pengguna.'
                ]
            ]
        ];

        $output = [];
        $i = 1;
        foreach ($services as $s) {
            $output[] = [
                'nomor' => $i++,
                'nama_layanan' => $s['title'],
                'slug' => $s['slug'],
                'deskripsi_singkat' => $s['desc'],
                'deskripsi_lengkap' => json_encode([
                    'solutions' => $s['solutions'],
                    'benefits' => $s['benefits']
                ]),
                'icon' => $s['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('services')->insert($output);
    }
}
