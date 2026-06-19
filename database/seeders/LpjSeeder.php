<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LpjSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        if (!$userId) {
            $this->command->warn('Tidak ada user. Register dulu di /register.');
            return;
        }

        $lpjId = DB::table('lpjs')->insertGetId([
            'user_id'               => $userId,
            'nama_kegiatan'         => 'Orbit Star #1',
            'akronim'               => 'OS1',
            'tema_kegiatan'         => 'Learning Together, Growing Stronger',
            'penyelenggara'         => 'UKM Robotika Telkom University',
            'afiliasi'              => 'Direktorat Kemahasiswaan',
            'tanggal_mulai'         => '2025-03-15',
            'tanggal_selesai'       => '2025-03-15',
            'waktu_mulai'           => '08:00:00',
            'waktu_selesai'         => '17:00:00',
            'tempat_kegiatan'       => 'Aula Gedung Manterawu Lt. 5',
            'kota'                  => 'BANDUNG',
            'tahun'                 => '2025',
            'latar_belakang'        => 'Kegiatan Orbit Star #1 merupakan program kerja UKM Robotika Telkom University yang bertujuan untuk meningkatkan kompetensi anggota dalam bidang robotika dan teknologi terkini. Kegiatan ini dilaksanakan sebagai bentuk komitmen organisasi dalam mengembangkan sumber daya manusia yang unggul dan berkarakter.',
            'tujuan_kegiatan'       => json_encode([
                'Meningkatkan pengetahuan anggota UKM Robotika tentang teknologi terkini',
                'Mempererat tali silaturahmi antar anggota organisasi',
                'Mengembangkan kemampuan teknis dan soft skill peserta',
                'Memperkenalkan UKM Robotika kepada mahasiswa baru Telkom University',
            ]),
            'sasaran_kegiatan'      => 'Seluruh anggota aktif UKM Robotika Telkom University dan mahasiswa baru yang berminat bergabung, dengan total peserta yang diharapkan mencapai 100 orang.',
            'bentuk_kegiatan'       => 'Seminar, Workshop Praktikum Robotika, dan Sesi Networking antar anggota.',
            'deskripsi_pelaksanaan' => 'Kegiatan Orbit Star #1 berjalan dengan lancar sesuai rundown yang telah ditetapkan. Peserta sangat antusias mengikuti seluruh rangkaian acara mulai dari sesi seminar pagi hingga workshop praktikum di sore hari. Narasumber yang hadir memberikan materi yang sangat informatif dan interaktif. Beberapa kendala teknis seperti koneksi internet yang terganggu dapat diatasi dengan cepat oleh tim teknis.',
            'simpulan_rekomendasi'  => "Simpulan:\nKegiatan Orbit Star #1 telah terlaksana dengan baik dan mencapai target peserta yang ditetapkan. Seluruh sesi berjalan sesuai jadwal dengan tingkat kepuasan peserta yang tinggi.\n\nRekomendasi:\n1. Perbanyak sesi workshop praktikum pada kegiatan berikutnya\n2. Sediakan koneksi internet cadangan untuk menghindari kendala teknis\n3. Tambah waktu sesi networking agar peserta dapat berinteraksi lebih intensif",
            'penutup'               => 'Demikian Laporan Pertanggungjawaban kegiatan Orbit Star #1 ini kami susun sebagai bentuk pertanggungjawaban atas pelaksanaan kegiatan. Kami mengucapkan terima kasih kepada semua pihak yang telah mendukung terselenggaranya kegiatan ini.',
            'ketua_pelaksana_nama'  => 'Muhammad Rizky Pratama',
            'ketua_pelaksana_nim'   => '1103210001',
            'ketua_ukm_nama'        => 'Ahmad Fauzan',
            'ketua_ukm_nim'         => '1103200001',
            'pembina_1_nama'        => 'Dr. Ir. Budi Santoso, M.T.',
            'pembina_1_nip'         => '198501012010011001',
            'direktur_nama'         => 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'          => '20820005',
            'logo_organisasi_path'  => null,
            'status'                => 'draft',
            'created_at'            => Carbon::now(),
            'updated_at'            => Carbon::now(),
        ]);

        // Rundown
        $rundowns = [
            ['waktu_mulai' => '08:00', 'waktu_selesai' => '08:30', 'durasi' => '30 menit',  'detail_kegiatan' => 'Registrasi & Persiapan Peserta',      'pic' => 'Divisi Acara'],
            ['waktu_mulai' => '08:30', 'waktu_selesai' => '09:00', 'durasi' => '30 menit',  'detail_kegiatan' => 'Pembukaan & Sambutan Ketua UKM',       'pic' => 'MC'],
            ['waktu_mulai' => '09:00', 'waktu_selesai' => '10:30', 'durasi' => '1 jam 30 menit', 'detail_kegiatan' => 'Seminar: Tren Robotika 2025',     'pic' => 'Narasumber'],
            ['waktu_mulai' => '10:30', 'waktu_selesai' => '10:45', 'durasi' => '15 menit',  'detail_kegiatan' => 'Coffee Break',                          'pic' => 'Divisi Konsumsi'],
            ['waktu_mulai' => '10:45', 'waktu_selesai' => '12:00', 'durasi' => '1 jam 15 menit', 'detail_kegiatan' => 'Workshop Praktikum Robot Line Follower', 'pic' => 'Divisi Workshop'],
            ['waktu_mulai' => '12:00', 'waktu_selesai' => '13:00', 'durasi' => '1 jam',     'detail_kegiatan' => 'Ishoma',                                'pic' => 'Divisi Konsumsi'],
            ['waktu_mulai' => '13:00', 'waktu_selesai' => '15:00', 'durasi' => '2 jam',     'detail_kegiatan' => 'Workshop Praktikum Robot Arm',          'pic' => 'Divisi Workshop'],
            ['waktu_mulai' => '15:00', 'waktu_selesai' => '16:00', 'durasi' => '1 jam',     'detail_kegiatan' => 'Sesi Networking & Tanya Jawab',         'pic' => 'Ketua Panitia'],
            ['waktu_mulai' => '16:00', 'waktu_selesai' => '17:00', 'durasi' => '1 jam',     'detail_kegiatan' => 'Penutupan & Foto Bersama',              'pic' => 'MC'],
        ];
        foreach ($rundowns as $i => $r) {
            DB::table('lpj_rundowns')->insert(array_merge($r, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        // Analisis Risiko
        $risks = [
            [
                'uraian_kegiatan'     => 'Seminar Robotika',
                'identifikasi_bahaya' => 'Narasumber tidak hadir',
                'peluang'             => '2/5',
                'akibat'              => 'Jadwal acara terganggu',
                'tingkat_risiko'      => 'Rendah',
                'pengendalian_risiko' => 'Siapkan narasumber cadangan, konfirmasi H-3',
                'penanggung_jawab'    => 'Ketua Panitia',
            ],
            [
                'uraian_kegiatan'     => 'Workshop Praktikum',
                'identifikasi_bahaya' => 'Kerusakan peralatan robot',
                'peluang'             => '3/5',
                'akibat'              => 'Workshop tidak dapat berjalan',
                'tingkat_risiko'      => 'Sedang',
                'pengendalian_risiko' => 'Siapkan peralatan cadangan, cek kondisi H-1',
                'penanggung_jawab'    => 'Divisi Workshop',
            ],
            [
                'uraian_kegiatan'     => 'Seluruh Rangkaian Acara',
                'identifikasi_bahaya' => 'Jumlah peserta tidak mencapai target',
                'peluang'             => '2/5',
                'akibat'              => 'Target kegiatan tidak tercapai',
                'tingkat_risiko'      => 'Rendah',
                'pengendalian_risiko' => 'Promosi masif via media sosial dan koordinasi dengan Direktorat',
                'penanggung_jawab'    => 'Divisi Humas',
            ],
        ];
        foreach ($risks as $i => $r) {
            DB::table('lpj_risks')->insert(array_merge($r, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        // Monitoring dan Evaluasi: grup tanggal/fase -> banyak detail kegiatan
        $monitoringGroups = [
            [
                'tanggal' => '2025-03-01', 'fase' => 'Pra-Acara',
                'items' => [
                    ['detail_kegiatan' => 'Rapat koordinasi perdana panitia', 'pic' => 'Ketua Panitia', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Pembentukan divisi & pembagian tugas', 'pic' => 'Sekretaris', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Penyusunan timeline kegiatan', 'pic' => 'Ketua Panitia', 'keterangan' => 'Terlaksana'],
                ],
            ],
            [
                'tanggal' => '2025-03-08', 'fase' => 'Pra-Acara',
                'items' => [
                    ['detail_kegiatan' => 'Finalisasi proposal & pengajuan ke Ditmawa', 'pic' => 'Sekretaris', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Konfirmasi narasumber & venue', 'pic' => 'Divisi Acara', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Technical meeting seluruh panitia', 'pic' => 'Ketua Panitia', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Pengecekan peralatan & dekorasi venue', 'pic' => 'Divisi Workshop', 'keterangan' => 'Terlaksana'],
                ],
            ],
            [
                'tanggal' => '2025-03-15', 'fase' => 'Pelaksanaan',
                'items' => [
                    ['detail_kegiatan' => 'Pelaksanaan kegiatan Orbit Star #1', 'pic' => 'Seluruh Panitia', 'keterangan' => 'Terlaksana lancar'],
                ],
            ],
            [
                'tanggal' => '2025-03-22', 'fase' => 'Pasca-Acara',
                'items' => [
                    ['detail_kegiatan' => 'Evaluasi kegiatan & pengumpulan dokumentasi', 'pic' => 'Sekretaris', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Penyusunan LPJ', 'pic' => 'Sekretaris', 'keterangan' => 'Terlaksana'],
                ],
            ],
        ];
        foreach ($monitoringGroups as $gi => $g) {
            $groupId = DB::table('lpj_monitoring_groups')->insertGetId([
                'lpj_id'     => $lpjId,
                'urutan'     => $gi,
                'tanggal'    => $g['tanggal'],
                'fase'       => $g['fase'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            foreach ($g['items'] as $ii => $item) {
                DB::table('lpj_monitoring_items')->insert(array_merge($item, [
                    'lpj_monitoring_group_id' => $groupId,
                    'urutan'                  => $ii,
                    'created_at'              => Carbon::now(),
                    'updated_at'              => Carbon::now(),
                ]));
            }
        }

        // Struktur Kepanitiaan
        $committees = [
            ['jabatan' => 'Ketua Panitia',          'nama' => 'Muhammad Rizky Pratama', 'jurusan' => 'S1 Teknik Elektro',           'angkatan' => '2021', 'fakultas' => 'FTE', 'nim' => '1103210001'],
            ['jabatan' => 'Wakil Ketua',             'nama' => 'Dian Safitri',           'jurusan' => 'S1 Teknik Informatika',       'angkatan' => '2021', 'fakultas' => 'FIF', 'nim' => '1103210003'],
            ['jabatan' => 'Sekretaris I',            'nama' => 'Siti Nurhaliza',         'jurusan' => 'S1 Sistem Informasi',         'angkatan' => '2021', 'fakultas' => 'FIF', 'nim' => '1103210002'],
            ['jabatan' => 'Sekretaris II',           'nama' => 'Rina Marlina',           'jurusan' => 'S1 Teknik Industri',          'angkatan' => '2022', 'fakultas' => 'FRI', 'nim' => '1103220001'],
            ['jabatan' => 'Bendahara I',             'nama' => 'Dewi Lestari',           'jurusan' => 'S1 Manajemen Bisnis',         'angkatan' => '2021', 'fakultas' => 'FEB', 'nim' => '1103210004'],
            ['jabatan' => 'Koordinator Acara',       'nama' => 'Farhan Hidayat',         'jurusan' => 'S1 Teknik Komputer',          'angkatan' => '2022', 'fakultas' => 'FTE', 'nim' => '1103220002'],
            ['jabatan' => 'Koordinator Workshop',    'nama' => 'Bagas Wicaksono',        'jurusan' => 'S1 Teknik Elektro',           'angkatan' => '2022', 'fakultas' => 'FTE', 'nim' => '1103220003'],
            ['jabatan' => 'Koordinator Humas',       'nama' => 'Nabila Putri',           'jurusan' => 'S1 Ilmu Komunikasi',          'angkatan' => '2022', 'fakultas' => 'FKB', 'nim' => '1103220004'],
            ['jabatan' => 'Koordinator Konsumsi',    'nama' => 'Aulia Rahma',            'jurusan' => 'S1 Teknik Informatika',       'angkatan' => '2022', 'fakultas' => 'FIF', 'nim' => '1103220005'],
            ['jabatan' => 'Koordinator Dokumentasi', 'nama' => 'Kevin Aldrian',          'jurusan' => 'S1 Desain Komunikasi Visual', 'angkatan' => '2022', 'fakultas' => 'FIK', 'nim' => '1103220006'],
        ];
        foreach ($committees as $i => $c) {
            DB::table('lpj_committees')->insert(array_merge($c, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        // Anggaran
        $budgets = [
            // Dana Masuk
            ['jenis' => 'dana_masuk', 'sumber_dana' => 'Dana Kemahasiswaan Ditmawa',     'target' => 3000000, 'jumlah_total' => 3000000, 'urutan' => 1],
            ['jenis' => 'dana_masuk', 'sumber_dana' => 'Kas UKM Robotika',               'target' => 1500000, 'jumlah_total' => 1500000, 'urutan' => 2],
            ['jenis' => 'dana_masuk', 'sumber_dana' => 'Kontribusi Peserta (100 orang)', 'target' => 2500000, 'jumlah_total' => 2500000, 'urutan' => 3],
            // Dana Keluar
            ['jenis' => 'dana_keluar', 'divisi' => 'Umum',        'rincian_kebutuhan' => 'Sewa Aula Manterawu Lt. 5',       'kuantitas' => 1,   'satuan' => 'hari',   'harga_satuan' => 1500000, 'jumlah_total' => 1500000, 'urutan' => 1],
            ['jenis' => 'dana_keluar', 'divisi' => 'Acara',       'rincian_kebutuhan' => 'Honor Narasumber',                 'kuantitas' => 2,   'satuan' => 'orang',  'harga_satuan' => 750000,  'jumlah_total' => 1500000, 'urutan' => 2],
            ['jenis' => 'dana_keluar', 'divisi' => 'Konsumsi',    'rincian_kebutuhan' => 'Konsumsi Peserta (snack + makan)', 'kuantitas' => 100, 'satuan' => 'orang',  'harga_satuan' => 35000,   'jumlah_total' => 3500000, 'urutan' => 3],
            ['jenis' => 'dana_keluar', 'divisi' => 'Workshop',    'rincian_kebutuhan' => 'Komponen Robot Praktikum',         'kuantitas' => 5,   'satuan' => 'set',    'harga_satuan' => 150000,  'jumlah_total' => 750000,  'urutan' => 4],
            ['jenis' => 'dana_keluar', 'divisi' => 'Perlengkapan','rincian_kebutuhan' => 'Dekorasi & Backdrop',              'kuantitas' => 1,   'satuan' => 'paket',  'harga_satuan' => 500000,  'jumlah_total' => 500000,  'urutan' => 5],
            ['jenis' => 'dana_keluar', 'divisi' => 'Sekretariat', 'rincian_kebutuhan' => 'Sertifikat Peserta (cetak)',       'kuantitas' => 100, 'satuan' => 'lembar', 'harga_satuan' => 3500,    'jumlah_total' => 350000,  'urutan' => 6],
            ['jenis' => 'dana_keluar', 'divisi' => 'Medkom',      'rincian_kebutuhan' => 'Dokumentasi & Desain',             'kuantitas' => 1,   'satuan' => 'paket',  'harga_satuan' => 400000,  'jumlah_total' => 400000,  'urutan' => 7],
        ];
        foreach ($budgets as $b) {
            DB::table('lpj_budgets')->insert(array_merge($b, [
                'lpj_id'     => $lpjId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $this->command->info("LPJ seeder berhasil! LPJ ID: {$lpjId}");
        $this->command->line("   Preview : http://localhost:8000/lpj/{$lpjId}/preview");
        $this->command->line("   Generate: http://localhost:8000/lpj/{$lpjId}/generate");
    }
}
