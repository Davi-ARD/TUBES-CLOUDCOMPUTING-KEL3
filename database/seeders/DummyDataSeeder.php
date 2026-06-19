<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\ProposalBudget;
use App\Models\ProposalCommittee;
use App\Models\ProposalRisk;
use App\Models\ProposalRundown;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkomuniversity.ac.id'],
            [
                'name'         => 'Administrator Demo',
                'password'     => Hash::make('password123'),
                'organization' => 'Direktorat Kemahasiswaan',
                'position'     => 'Administrator',
            ]
        );

        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@telkomuniversity.ac.id'],
            [
                'name'         => 'Budi Santoso',
                'password'     => Hash::make('password123'),
                'organization' => 'UKM Robotika Telkom University',
                'position'     => 'Ketua Pelaksana',
            ]
        );

        User::firstOrCreate(
            ['email' => 'ukm@telkomuniversity.ac.id'],
            [
                'name'         => 'Ketua UKM Demo',
                'password'     => Hash::make('password123'),
                'organization' => 'UKM Paduan Suara Telkom University',
                'position'     => 'Presiden UKM',
            ]
        );

        $this->seedProposalSatu($mahasiswa->id);
        $this->seedProposalDua($mahasiswa->id);
        $this->seedLpj($mahasiswa->id);

        $this->command->info('DummyDataSeeder selesai: 3 user, 2 proposal, 1 LPJ.');
    }

    private function seedProposalSatu(int $userId): void
    {
        if (Proposal::where('user_id', $userId)->where('nama_kegiatan', 'Workshop Internet of Things untuk Pemula')->exists()) {
            return;
        }

        $proposal = Proposal::create([
            'user_id'              => $userId,
            'nama_kegiatan'        => 'Workshop Internet of Things untuk Pemula',
            'tema_kegiatan'        => 'Membangun Solusi Cerdas dari Rumah',
            'penyelenggara'        => 'UKM Robotika Telkom University',
            'afiliasi'             => 'Direktorat Kemahasiswaan Telkom University',
            'tanggal_mulai'        => '2026-06-10',
            'tanggal_selesai'      => '2026-06-10',
            'waktu_mulai'          => '08:00',
            'waktu_selesai'        => '16:00',
            'tempat_kegiatan'      => 'Gedung Tokong Nanas, Telkom University',
            'kota'                 => 'BANDUNG',
            'tahun'                => 2026,
            'latar_belakang'       => 'Perkembangan teknologi Internet of Things membuka peluang besar bagi mahasiswa untuk menciptakan solusi cerdas di berbagai bidang. UKM Robotika Telkom University memandang perlu menyelenggarakan workshop pengenalan IoT agar mahasiswa memiliki fondasi praktis dalam merancang perangkat terhubung.',
            'tujuan_kegiatan'      => [
                'Memperkenalkan konsep dasar Internet of Things kepada mahasiswa',
                'Melatih peserta merakit dan memprogram perangkat IoT sederhana',
                'Mendorong lahirnya ide proyek IoT yang dapat dikembangkan lebih lanjut',
            ],
            'sasaran_kegiatan'     => 'Mahasiswa aktif Telkom University dari seluruh fakultas, dengan target 60 peserta.',
            'bentuk_kegiatan'      => 'Workshop praktik dengan pendampingan mentor dan sesi tanya jawab.',
            'materi_kegiatan'      => [
                ['judul' => 'Pengantar IoT', 'deskripsi' => 'Konsep dasar, arsitektur, dan contoh penerapan IoT di kehidupan sehari-hari.'],
                ['judul' => 'Praktik Sensor dan Mikrokontroler', 'deskripsi' => 'Merangkai sensor dengan mikrokontroler dan membaca data secara real time.'],
                ['judul' => 'Integrasi Cloud', 'deskripsi' => 'Mengirim data perangkat ke layanan cloud dan menampilkannya pada dashboard.'],
            ],
            'narasumber_kegiatan'  => 'Praktisi IoT dari industri serta mentor internal UKM Robotika.',
            'monitoring_evaluasi'  => "Monitoring dilakukan melalui presensi peserta dan kuesioner kepuasan. Indikator keberhasilan:\nKehadiran minimal 80 persen peserta terdaftar.\nSkor kepuasan rata-rata minimal 4 dari 5.",
            'penutup'              => 'Demikian proposal ini kami susun. Besar harapan kami kegiatan ini mendapat dukungan dari Direktorat Kemahasiswaan Telkom University.',
            'president_ukm_nama'   => 'Ahmad Fauzan',
            'president_ukm_nim'    => '1103200001',
            'sekretaris_nama'      => 'Siti Nurhaliza',
            'sekretaris_nim'       => '1103210002',
            'ketua_pelaksana_nama' => 'Budi Santoso',
            'ketua_pelaksana_nim'  => '1103210001',
            'pembina_nama'         => 'Dr. Ir. Budi Hartono, M.T.',
            'pembina_nip'          => '198501012010011001',
            'status'               => 'draft',
        ]);

        $rundowns = [
            ['08:00', '08:30', 30, 'Registrasi peserta'],
            ['08:30', '09:00', 30, 'Pembukaan dan sambutan'],
            ['09:00', '10:30', 90, 'Materi: Pengantar IoT'],
            ['10:30', '12:00', 90, 'Praktik sensor dan mikrokontroler'],
            ['13:00', '15:00', 120, 'Praktik integrasi cloud'],
            ['15:00', '16:00', 60, 'Presentasi proyek dan penutupan'],
        ];
        foreach ($rundowns as $i => [$mulai, $selesai, $durasi, $aktivitas]) {
            ProposalRundown::create([
                'proposal_id'   => $proposal->id,
                'urutan'        => $i,
                'waktu_mulai'   => $mulai . ':00',
                'waktu_selesai' => $selesai . ':00',
                'durasi_menit'  => $durasi,
                'aktivitas'     => $aktivitas,
            ]);
        }

        $risks = [
            ['Praktik perangkat', 'Kerusakan modul sensor', '4/5', '5/5', 'DANGER', 'Sediakan modul cadangan dan cek kondisi sebelum acara', 'Divisi Perlengkapan'],
            ['Sesi materi', 'Narasumber berhalangan hadir', '3/5', '3/5', 'MEDIUM', 'Konfirmasi ulang H-3 dan siapkan mentor pengganti', 'Ketua Pelaksana'],
            ['Keseluruhan acara', 'Peserta kurang dari target', '1/5', '2/5', 'LOW', 'Promosi melalui media sosial dan kerja sama himpunan', 'Divisi Humas'],
        ];
        foreach ($risks as $i => [$uraian, $bahaya, $peluang, $akibat, $tingkat, $pengendalian, $pj]) {
            ProposalRisk::create([
                'proposal_id'         => $proposal->id,
                'urutan'              => $i,
                'uraian_kegiatan'     => $uraian,
                'identifikasi_bahaya' => $bahaya,
                'peluang'             => $peluang,
                'akibat'              => $akibat,
                'tingkat_risiko'      => $tingkat,
                'pengendalian_risiko' => $pengendalian,
                'penanggung_jawab'    => $pj,
            ]);
        }

        $committees = [
            ['Ketua Pelaksana', 'Budi Santoso', 'S1 Teknik Elektro', '2021', 'FTE', '1103210001'],
            ['Sekretaris', 'Siti Nurhaliza', 'S1 Sistem Informasi', '2021', 'FIF', '1103210002'],
            ['Bendahara', 'Dewi Lestari', 'S1 Manajemen Bisnis', '2021', 'FEB', '1103210004'],
            ['Koordinator Acara', 'Farhan Hidayat', 'S1 Teknik Komputer', '2022', 'FTE', '1103220002'],
            ['Koordinator Perlengkapan', 'Bagas Wicaksono', 'S1 Teknik Elektro', '2022', 'FTE', '1103220003'],
            ['Koordinator Humas', 'Nabila Putri', 'S1 Ilmu Komunikasi', '2022', 'FKB', '1103220004'],
        ];
        foreach ($committees as $i => [$jabatan, $nama, $jurusan, $angkatan, $fakultas, $nim]) {
            ProposalCommittee::create([
                'proposal_id'    => $proposal->id,
                'urutan'         => $i,
                'jabatan'        => $jabatan,
                'nama'           => $nama,
                'jurusan'        => $jurusan,
                'tahun_angkatan' => $angkatan,
                'fakultas'       => $fakultas,
                'nim'            => $nim,
            ]);
        }

        $pemasukan = [
            ['Dana Kemahasiswaan Direktorat', 3000000],
            ['Kontribusi peserta', 1800000],
        ];
        foreach ($pemasukan as $i => [$ket, $total]) {
            ProposalBudget::create([
                'proposal_id' => $proposal->id,
                'jenis'       => 'pemasukan',
                'keterangan'  => $ket,
                'total'       => $total,
                'urutan'      => $i,
            ]);
        }

        $pengeluaran = [
            ['Sewa ruang dan proyektor', 1, 'paket', 1000000],
            ['Modul sensor dan mikrokontroler', 15, 'set', 120000],
            ['Konsumsi peserta', 60, 'orang', 30000],
            ['Sertifikat peserta', 60, 'lembar', 4000],
        ];
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $i => [$ket, $qty, $satuan, $harga]) {
            $total = $qty * $harga;
            $totalPengeluaran += $total;
            ProposalBudget::create([
                'proposal_id'  => $proposal->id,
                'jenis'        => 'pengeluaran',
                'keterangan'   => $ket,
                'kuantitas'    => $qty,
                'satuan'       => $satuan,
                'harga_satuan' => $harga,
                'total'        => $total,
                'urutan'       => $i,
            ]);
        }

        ProposalBudget::create([
            'proposal_id' => $proposal->id,
            'jenis'       => 'sumber_dana',
            'keterangan'  => 'Direktorat Kemahasiswaan dan kontribusi peserta',
            'total'       => 4800000,
            'urutan'      => 0,
        ]);

        $proposal->update(['total_anggaran' => $totalPengeluaran]);
    }

    private function seedProposalDua(int $userId): void
    {
        if (Proposal::where('user_id', $userId)->where('nama_kegiatan', 'Seminar Kepemimpinan Mahasiswa')->exists()) {
            return;
        }

        $proposal = Proposal::create([
            'user_id'              => $userId,
            'nama_kegiatan'        => 'Seminar Kepemimpinan Mahasiswa',
            'tema_kegiatan'        => 'Memimpin dengan Empati di Era Digital',
            'penyelenggara'        => 'UKM Robotika Telkom University',
            'afiliasi'             => 'Direktorat Kemahasiswaan Telkom University',
            'tanggal_mulai'        => '2026-07-20',
            'tanggal_selesai'      => '2026-07-21',
            'waktu_mulai'          => '09:00',
            'waktu_selesai'        => '15:00',
            'tempat_kegiatan'      => 'Aula Gedung Manterawu Lantai 5',
            'kota'                 => 'BANDUNG',
            'tahun'                => 2026,
            'latar_belakang'       => 'Kepemimpinan menjadi keterampilan penting bagi mahasiswa dalam menghadapi tantangan organisasi maupun dunia kerja. Seminar ini dirancang untuk membekali peserta dengan wawasan kepemimpinan yang relevan dengan perkembangan zaman.',
            'tujuan_kegiatan'      => [
                'Meningkatkan pemahaman peserta tentang prinsip kepemimpinan modern',
                'Mendorong peserta menerapkan kepemimpinan berbasis empati',
                'Membangun jejaring antar organisasi mahasiswa',
            ],
            'sasaran_kegiatan'     => 'Pengurus organisasi mahasiswa Telkom University dengan target 80 peserta.',
            'bentuk_kegiatan'      => 'Seminar dan diskusi panel bersama narasumber.',
            'materi_kegiatan'      => [
                ['judul' => 'Kepemimpinan di Era Digital', 'deskripsi' => 'Tantangan dan peluang memimpin organisasi pada masa transformasi digital.'],
                ['judul' => 'Komunikasi Empatik', 'deskripsi' => 'Teknik komunikasi yang membangun kepercayaan dalam tim.'],
            ],
            'narasumber_kegiatan'  => 'Praktisi pengembangan diri dan alumni penggerak organisasi.',
            'monitoring_evaluasi'  => 'Evaluasi dilakukan melalui kuesioner dan rekap kehadiran peserta pada setiap sesi.',
            'penutup'              => 'Demikian proposal ini disusun sebagai dasar pelaksanaan kegiatan. Atas dukungan Direktorat Kemahasiswaan kami ucapkan terima kasih.',
            'president_ukm_nama'   => 'Ahmad Fauzan',
            'president_ukm_nim'    => '1103200001',
            'sekretaris_nama'      => 'Rina Marlina',
            'sekretaris_nim'       => '1103220001',
            'ketua_pelaksana_nama' => 'Budi Santoso',
            'ketua_pelaksana_nim'  => '1103210001',
            'pembina_nama'         => 'Dr. Ir. Budi Hartono, M.T.',
            'pembina_nip'          => '198501012010011001',
            'status'               => 'generated',
            'generated_at'         => Carbon::now()->subDays(3),
        ]);

        $rundowns = [
            ['09:00', '09:30', 30, 'Registrasi dan pembukaan'],
            ['09:30', '11:00', 90, 'Sesi 1: Kepemimpinan di era digital'],
            ['11:00', '12:30', 90, 'Sesi 2: Komunikasi empatik'],
            ['13:30', '15:00', 90, 'Diskusi panel dan penutupan'],
        ];
        foreach ($rundowns as $i => [$mulai, $selesai, $durasi, $aktivitas]) {
            ProposalRundown::create([
                'proposal_id'   => $proposal->id,
                'urutan'        => $i,
                'waktu_mulai'   => $mulai . ':00',
                'waktu_selesai' => $selesai . ':00',
                'durasi_menit'  => $durasi,
                'aktivitas'     => $aktivitas,
            ]);
        }

        ProposalRisk::create([
            'proposal_id'         => $proposal->id,
            'urutan'              => 0,
            'uraian_kegiatan'     => 'Seminar utama',
            'identifikasi_bahaya' => 'Narasumber terlambat',
            'peluang'             => '3/5',
            'akibat'              => '4/5',
            'tingkat_risiko'      => 'HIGH',
            'pengendalian_risiko' => 'Koordinasi jadwal H-1 dan sediakan moderator pengisi waktu',
            'penanggung_jawab'    => 'Divisi Acara',
        ]);

        $committees = [
            ['Ketua Pelaksana', 'Budi Santoso', 'S1 Teknik Elektro', '2021', 'FTE', '1103210001'],
            ['Sekretaris', 'Rina Marlina', 'S1 Teknik Industri', '2022', 'FRI', '1103220001'],
            ['Bendahara', 'Dewi Lestari', 'S1 Manajemen Bisnis', '2021', 'FEB', '1103210004'],
            ['Koordinator Acara', 'Farhan Hidayat', 'S1 Teknik Komputer', '2022', 'FTE', '1103220002'],
        ];
        foreach ($committees as $i => [$jabatan, $nama, $jurusan, $angkatan, $fakultas, $nim]) {
            ProposalCommittee::create([
                'proposal_id'    => $proposal->id,
                'urutan'         => $i,
                'jabatan'        => $jabatan,
                'nama'           => $nama,
                'jurusan'        => $jurusan,
                'tahun_angkatan' => $angkatan,
                'fakultas'       => $fakultas,
                'nim'            => $nim,
            ]);
        }

        ProposalBudget::create([
            'proposal_id' => $proposal->id,
            'jenis'       => 'pemasukan',
            'keterangan'  => 'Dana Kemahasiswaan Direktorat',
            'total'       => 5000000,
            'urutan'      => 0,
        ]);

        $pengeluaran = [
            ['Honor narasumber', 2, 'orang', 1000000],
            ['Sewa aula dan perlengkapan', 1, 'paket', 1500000],
            ['Konsumsi peserta', 80, 'orang', 25000],
        ];
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $i => [$ket, $qty, $satuan, $harga]) {
            $total = $qty * $harga;
            $totalPengeluaran += $total;
            ProposalBudget::create([
                'proposal_id'  => $proposal->id,
                'jenis'        => 'pengeluaran',
                'keterangan'   => $ket,
                'kuantitas'    => $qty,
                'satuan'       => $satuan,
                'harga_satuan' => $harga,
                'total'        => $total,
                'urutan'       => $i,
            ]);
        }

        $proposal->update(['total_anggaran' => $totalPengeluaran]);
    }

    private function seedLpj(int $userId): void
    {
        $exists = DB::table('lpjs')
            ->where('user_id', $userId)
            ->where('nama_kegiatan', 'Workshop Internet of Things untuk Pemula')
            ->exists();
        if ($exists) {
            return;
        }

        $now = Carbon::now();

        $lpjId = DB::table('lpjs')->insertGetId([
            'user_id'               => $userId,
            'nama_kegiatan'         => 'Workshop Internet of Things untuk Pemula',
            'akronim'               => 'WIoT',
            'tema_kegiatan'         => 'Membangun Solusi Cerdas dari Rumah',
            'penyelenggara'         => 'UKM Robotika Telkom University',
            'afiliasi'              => 'Direktorat Kemahasiswaan Telkom University',
            'tanggal_mulai'         => '2026-06-10',
            'tanggal_selesai'       => '2026-06-10',
            'waktu_mulai'           => '08:00:00',
            'waktu_selesai'         => '16:00:00',
            'tempat_kegiatan'       => 'Gedung Tokong Nanas, Telkom University',
            'kota'                  => 'BANDUNG',
            'tahun'                 => '2026',
            'latar_belakang'        => 'Workshop Internet of Things untuk Pemula diselenggarakan untuk membekali mahasiswa dengan keterampilan praktis merancang perangkat terhubung. Laporan ini disusun sebagai bentuk pertanggungjawaban atas pelaksanaan kegiatan.',
            'tujuan_kegiatan'       => json_encode([
                'Memperkenalkan konsep dasar Internet of Things kepada mahasiswa',
                'Melatih peserta merakit dan memprogram perangkat IoT sederhana',
                'Mendorong lahirnya ide proyek IoT yang dapat dikembangkan lebih lanjut',
            ]),
            'sasaran_kegiatan'      => 'Mahasiswa aktif Telkom University dari seluruh fakultas, dengan realisasi 58 peserta hadir.',
            'bentuk_kegiatan'       => 'Workshop praktik dengan pendampingan mentor dan sesi tanya jawab.',
            'deskripsi_pelaksanaan' => 'Kegiatan berjalan lancar sesuai rundown. Peserta antusias mengikuti seluruh sesi, mulai dari materi pengantar hingga praktik integrasi cloud. Kendala koneksi internet pada sesi cloud dapat diatasi dengan jaringan cadangan.',
            'simpulan_rekomendasi'  => "Simpulan:\nKegiatan terlaksana dengan baik dengan tingkat kehadiran 58 dari 60 peserta terdaftar.\n\nRekomendasi:\nSediakan jaringan internet cadangan sejak awal dan perbanyak sesi praktik pada kegiatan berikutnya.",
            'penutup'               => 'Demikian Laporan Pertanggungjawaban ini kami susun. Terima kasih atas dukungan seluruh pihak yang terlibat.',
            'ketua_pelaksana_nama'  => 'Budi Santoso',
            'ketua_pelaksana_nim'   => '1103210001',
            'ketua_ukm_nama'        => 'Ahmad Fauzan',
            'ketua_ukm_nim'         => '1103200001',
            'pembina_1_nama'        => 'Dr. Ir. Budi Hartono, M.T.',
            'pembina_1_nip'         => '198501012010011001',
            'direktur_nama'         => 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'          => '20820005',
            'status'                => 'generated',
            'generated_at'          => $now->copy()->subDays(2),
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $rundowns = [
            ['waktu_mulai' => '08:00', 'waktu_selesai' => '08:30', 'durasi' => '30 menit',     'detail_kegiatan' => 'Registrasi peserta',                  'pic' => 'Divisi Acara'],
            ['waktu_mulai' => '08:30', 'waktu_selesai' => '09:00', 'durasi' => '30 menit',     'detail_kegiatan' => 'Pembukaan dan sambutan',              'pic' => 'MC'],
            ['waktu_mulai' => '09:00', 'waktu_selesai' => '10:30', 'durasi' => '1 jam 30 menit', 'detail_kegiatan' => 'Materi pengantar IoT',              'pic' => 'Narasumber'],
            ['waktu_mulai' => '10:30', 'waktu_selesai' => '12:00', 'durasi' => '1 jam 30 menit', 'detail_kegiatan' => 'Praktik sensor dan mikrokontroler', 'pic' => 'Divisi Workshop'],
            ['waktu_mulai' => '13:00', 'waktu_selesai' => '15:00', 'durasi' => '2 jam',        'detail_kegiatan' => 'Praktik integrasi cloud',             'pic' => 'Divisi Workshop'],
            ['waktu_mulai' => '15:00', 'waktu_selesai' => '16:00', 'durasi' => '1 jam',        'detail_kegiatan' => 'Presentasi proyek dan penutupan',     'pic' => 'Ketua Panitia'],
        ];
        foreach ($rundowns as $i => $r) {
            DB::table('lpj_rundowns')->insert(array_merge($r, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $risks = [
            ['uraian_kegiatan' => 'Praktik perangkat', 'identifikasi_bahaya' => 'Kerusakan modul sensor', 'peluang' => '4/5', 'akibat' => '3/5', 'tingkat_risiko' => 'HIGH', 'pengendalian_risiko' => 'Modul cadangan disiapkan', 'penanggung_jawab' => 'Divisi Perlengkapan'],
            ['uraian_kegiatan' => 'Sesi cloud',        'identifikasi_bahaya' => 'Gangguan koneksi internet', 'peluang' => '2/5', 'akibat' => '2/5', 'tingkat_risiko' => 'MEDIUM', 'pengendalian_risiko' => 'Jaringan cadangan aktif', 'penanggung_jawab' => 'Divisi Teknis'],
        ];
        foreach ($risks as $i => $r) {
            DB::table('lpj_risks')->insert(array_merge($r, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $monitoringGroups = [
            [
                'tanggal' => '2026-05-25', 'fase' => 'Pra-Acara',
                'items' => [
                    ['detail_kegiatan' => 'Rapat koordinasi panitia',        'pic' => 'Ketua Panitia', 'keterangan' => 'Terlaksana'],
                    ['detail_kegiatan' => 'Konfirmasi narasumber dan venue', 'pic' => 'Divisi Acara',  'keterangan' => 'Terlaksana'],
                ],
            ],
            [
                'tanggal' => '2026-06-10', 'fase' => 'Pelaksanaan',
                'items' => [
                    ['detail_kegiatan' => 'Pelaksanaan workshop', 'pic' => 'Seluruh Panitia', 'keterangan' => 'Terlaksana lancar'],
                ],
            ],
            [
                'tanggal' => '2026-06-14', 'fase' => 'Pasca-Acara',
                'items' => [
                    ['detail_kegiatan' => 'Penyusunan laporan', 'pic' => 'Sekretaris', 'keterangan' => 'Terlaksana'],
                ],
            ],
        ];
        foreach ($monitoringGroups as $gi => $g) {
            $groupId = DB::table('lpj_monitoring_groups')->insertGetId([
                'lpj_id'     => $lpjId,
                'urutan'     => $gi,
                'tanggal'    => $g['tanggal'],
                'fase'       => $g['fase'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            foreach ($g['items'] as $ii => $item) {
                DB::table('lpj_monitoring_items')->insert(array_merge($item, [
                    'lpj_monitoring_group_id' => $groupId,
                    'urutan'                  => $ii,
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ]));
            }
        }

        $committees = [
            ['jabatan' => 'Ketua Panitia',          'nama' => 'Budi Santoso',   'jurusan' => 'S1 Teknik Elektro',     'angkatan' => '2021', 'fakultas' => 'FTE', 'nim' => '1103210001'],
            ['jabatan' => 'Sekretaris',             'nama' => 'Siti Nurhaliza', 'jurusan' => 'S1 Sistem Informasi',   'angkatan' => '2021', 'fakultas' => 'FIF', 'nim' => '1103210002'],
            ['jabatan' => 'Bendahara',              'nama' => 'Dewi Lestari',   'jurusan' => 'S1 Manajemen Bisnis',   'angkatan' => '2021', 'fakultas' => 'FEB', 'nim' => '1103210004'],
            ['jabatan' => 'Koordinator Workshop',   'nama' => 'Bagas Wicaksono','jurusan' => 'S1 Teknik Elektro',     'angkatan' => '2022', 'fakultas' => 'FTE', 'nim' => '1103220003'],
            ['jabatan' => 'Koordinator Konsumsi',   'nama' => 'Aulia Rahma',    'jurusan' => 'S1 Teknik Informatika', 'angkatan' => '2022', 'fakultas' => 'FIF', 'nim' => '1103220005'],
        ];
        foreach ($committees as $i => $c) {
            DB::table('lpj_committees')->insert(array_merge($c, [
                'lpj_id'     => $lpjId,
                'urutan'     => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $budgets = [
            ['jenis' => 'dana_masuk', 'sumber_dana' => 'Dana Kemahasiswaan Direktorat', 'target' => 3000000, 'jumlah_total' => 3000000, 'urutan' => 1],
            ['jenis' => 'dana_masuk', 'sumber_dana' => 'Kontribusi peserta',            'target' => 1800000, 'jumlah_total' => 1740000, 'urutan' => 2],
            ['jenis' => 'dana_keluar', 'divisi' => 'Umum',         'rincian_kebutuhan' => 'Sewa ruang dan proyektor',           'kuantitas' => 1,  'satuan' => 'paket', 'harga_satuan' => 1000000, 'jumlah_total' => 1000000, 'urutan' => 1],
            ['jenis' => 'dana_keluar', 'divisi' => 'Workshop',     'rincian_kebutuhan' => 'Modul sensor dan mikrokontroler',     'kuantitas' => 15, 'satuan' => 'set',   'harga_satuan' => 120000,  'jumlah_total' => 1800000, 'urutan' => 2],
            ['jenis' => 'dana_keluar', 'divisi' => 'Konsumsi',     'rincian_kebutuhan' => 'Konsumsi peserta',                   'kuantitas' => 58, 'satuan' => 'orang', 'harga_satuan' => 30000,   'jumlah_total' => 1740000, 'urutan' => 3],
            ['jenis' => 'dana_keluar', 'divisi' => 'Sekretariat',  'rincian_kebutuhan' => 'Sertifikat peserta',                 'kuantitas' => 58, 'satuan' => 'lembar','harga_satuan' => 4000,    'jumlah_total' => 232000,  'urutan' => 4],
        ];
        foreach ($budgets as $b) {
            DB::table('lpj_budgets')->insert(array_merge($b, [
                'lpj_id'     => $lpjId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
