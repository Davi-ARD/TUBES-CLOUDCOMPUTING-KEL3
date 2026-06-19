<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\ProposalRundown;
use App\Models\ProposalRisk;
use App\Models\ProposalCommittee;
use App\Models\ProposalBudget;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user demo jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'demo@telkomuniversity.ac.id'],
            [
                'name'         => 'Demo User',
                'password'     => Hash::make('password123'),
                'organization' => 'SRE Telkom University',
                'position'     => 'Ketua Pelaksana',
            ]
        );

        $proposal = Proposal::create([
            'user_id'              => $user->id,
            'nama_kegiatan'        => 'Orbit Star #1 Studi Banding SRE TEL-U X DIGISTAR TEL-U',
            'tema_kegiatan'        => 'Learning together, growing stronger',
            'penyelenggara'        => 'Digistar Club Campus Chapter Telkom University',
            'afiliasi'             => 'Digistar Telkom University dan SRE Telkom University',
            'tanggal_mulai'        => '2026-03-11',
            'tanggal_selesai'      => '2026-04-11',
            'waktu_mulai'          => '18:30',
            'waktu_selesai'        => '20:32',
            'tempat_kegiatan'      => 'Teras Priangan - Outdoor Class',
            'kota'                 => 'BANDUNG',
            'tahun'                => 2026,
            'total_anggaran'       => 0,
            'pengajuan_dana'       => 0,
            'latar_belakang'       => "Dalam era digitalisasi yang semakin pesat, komunitas teknologi memiliki peran yang sangat penting dalam membentuk generasi penerus yang kompeten dan inovatif. SRE Telkom University (Site Reliability Engineering) dan Digistar Club Campus Chapter Telkom University merupakan dua komunitas teknologi yang aktif di lingkungan Telkom University, yang masing-masing memiliki keunggulan dan spesialisasi di bidangnya.\n\nMenyadari pentingnya kolaborasi dan pertukaran pengetahuan antar komunitas, kedua organisasi ini berinisiatif untuk menyelenggarakan sebuah kegiatan studi banding yang bertajuk \"Orbit Star #1\". Kegiatan ini dirancang sebagai wadah untuk saling berbagi pengalaman, best practices, dan wawasan teknis yang dapat memperkaya pengetahuan anggota dari kedua komunitas.\n\nMelalui kegiatan ini, diharapkan tercipta sinergi yang kuat antara SRE TEL-U dan Digistar TEL-U, sehingga dapat menghasilkan kolaborasi yang produktif dan berkelanjutan di masa mendatang.",
            'tujuan_kegiatan'      => [
                'Memperkenalkan budaya dan kegiatan SRE Telkom University kepada anggota Digistar Club',
                'Meningkatkan wawasan dan kemampuan teknis anggota kedua komunitas melalui pertukaran pengetahuan',
                'Membangun jaringan dan hubungan yang kuat antara SRE TEL-U dan Digistar TEL-U',
                'Menginspirasi anggota kedua komunitas untuk berkolaborasi dalam proyek-proyek teknologi',
            ],
            'sasaran_kegiatan'     => 'Seluruh pengurus dan anggota aktif Digistar Club Campus Chapter Telkom University dan SRE Telkom University',
            'bentuk_kegiatan'      => 'Studi banding dan sharing session interaktif dengan agenda presentasi program kerja, diskusi teknis, dan sesi networking',
            'materi_kegiatan'      => [
                ['judul' => 'Pengenalan SRE TEL-U', 'deskripsi' => 'Penjelasan tentang struktur organisasi, visi misi, dan program kerja unggulan SRE Telkom University'],
                ['judul' => 'Tech Talk: Site Reliability Engineering', 'deskripsi' => 'Sharing session tentang konsep dan praktik SRE dalam dunia industri teknologi modern'],
                ['judul' => 'Sesi Tanya Jawab & Networking', 'deskripsi' => 'Sesi interaktif untuk diskusi dan membangun koneksi antar anggota kedua komunitas'],
            ],
            'narasumber_kegiatan'  => 'Para pengurus serta stakeholder dari setiap student chapter yang hadir, dipandu oleh moderator dari internal SRE TEL-U',
            'monitoring_evaluasi'  => "Monitoring dan evaluasi kegiatan dilakukan melalui:\n• Presensi kehadiran peserta (target: minimal 80% dari total undangan)\n• Kuesioner kepuasan peserta pasca kegiatan (target: skor rata-rata ≥ 4/5)\n• Dokumentasi foto dan video kegiatan\n• Laporan pertanggungjawaban yang akan disusun dalam waktu 14 hari setelah kegiatan\n• Evaluasi capaian tujuan kegiatan oleh panitia",
            'penutup'              => 'Demikian proposal kegiatan Orbit Star #1 Studi Banding SRE TEL-U X Digistar TEL-U ini kami susun dengan sebaik-baiknya. Kami berharap kegiatan ini dapat terlaksana dengan baik dan mendapat dukungan penuh dari Direktorat Kemahasiswaan, Karier dan Alumni Telkom University. Atas perhatian dan dukungannya, kami mengucapkan terima kasih.',
            'president_ukm_nama'   => 'Muhammad Rizky Pratama',
            'president_ukm_nim'    => '1301210001',
            'sekretaris_nama'      => 'Siti Nurhaliza',
            'sekretaris_nim'       => '1301210042',
            'ketua_pelaksana_nama' => 'Ahmad Fauzi',
            'ketua_pelaksana_nim'  => '1301210087',
            'pembina_nama'         => 'Dr. Ir. Budi Santoso, M.T.',
            'pembina_nip'          => '19850612201001001',
            'direktur_nama'        => 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'         => '20820005',
            'logo_organisasi_path' => null,
            'status'               => 'draft',
        ]);

        // Rundown (11 baris)
        $rundowns = [
            ['07:30', '08:00', 30, 'Registrasi Peserta'],
            ['08:00', '08:15', 15, 'Pembukaan & Sambutan Ketua Pelaksana'],
            ['08:15', '08:30', 15, 'Sambutan Perwakilan Digistar Club'],
            ['08:30', '09:00', 30, 'Presentasi: Pengenalan SRE Telkom University'],
            ['09:00', '09:30', 30, 'Presentasi: Program Kerja & Pencapaian SRE TEL-U'],
            ['09:30', '10:00', 30, 'Tech Talk: Site Reliability Engineering in Practice'],
            ['10:00', '10:15', 15, 'Coffee Break & Networking'],
            ['10:15', '11:00', 45, 'Sesi Tanya Jawab Interaktif'],
            ['11:00', '11:30', 30, 'Workshop Singkat: Tools & Best Practices'],
            ['11:30', '11:50', 20, 'Diskusi Rencana Kolaborasi Mendatang'],
            ['11:50', '12:00', 10, 'Penutupan & Foto Bersama'],
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

        // Analisis Risiko (6 item)
        $risks = [
            ['Pembukaan Acara', 'Keterlambatan peserta', '3/5', '2/5', '3/3', 'Briefing panitia H-1, registrasi dibuka 30 menit lebih awal', 'Sie Acara'],
            ['Presentasi', 'Gangguan teknis (proyektor/laptop)', '2/5', '3/5', '2/3', 'Siapkan laptop cadangan dan test semua peralatan H-1', 'Sie Perlengkapan'],
            ['Sesi Diskusi', 'Peserta pasif/tidak antusias', '3/5', '2/5', '3/3', 'Siapkan ice breaker dan moderator yang aktif', 'Moderator'],
            ['Acara Keseluruhan', 'Pembatalan mendadak oleh narasumber', '2/5', '4/5', '2/4', 'Konfirmasi ulang H-3 dan H-1, siapkan narasumber pengganti', 'Ketua Pelaksana'],
            ['Keamanan Venue', 'Kecelakaan/insiden ringan', '1/5', '3/5', '1/3', 'Sediakan P3K dan pastikan venue bebas bahaya', 'Sie Keamanan'],
            ['Dokumentasi', 'Kegagalan alat dokumentasi', '2/5', '2/5', '2/2', 'Gunakan minimal 2 kamera/handphone untuk dokumentasi', 'Sie Dokumentasi'],
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

        // Kepanitiaan (19 anggota)
        $committees = [
            ['Ketua Pelaksana', 'Ahmad Fauzi', 'S1 Informatika', '2021', 'FIT', '1301210087'],
            ['Sekretaris', 'Siti Nurhaliza', 'S1 Sistem Informasi', '2021', 'FIT', '1301210042'],
            ['Bendahara', 'Budi Prasetyo', 'S1 Informatika', '2022', 'FIT', '1301220015'],
            ['Sie Acara', 'Dewi Anggraeni', 'S1 Informatika', '2022', 'FIT', '1301220031'],
            ['Sie Acara', 'Rizal Firmansyah', 'S1 Sistem Informasi', '2022', 'FIT', '1301220058'],
            ['Sie Konsumsi', 'Nia Rahmawati', 'S1 Informatika', '2022', 'FIT', '1301220074'],
            ['Sie Konsumsi', 'Taufik Hidayat', 'S1 Teknik Elektro', '2022', 'FTE', '1302220012'],
            ['Sie Perlengkapan', 'Hendra Gunawan', 'S1 Informatika', '2021', 'FIT', '1301210103'],
            ['Sie Perlengkapan', 'Yuliana Putri', 'S1 Sistem Informasi', '2022', 'FIT', '1301220089'],
            ['Sie Dokumentasi', 'Kevin Santoso', 'S1 Informatika', '2022', 'FIT', '1301220126'],
            ['Sie Dokumentasi', 'Mega Lestari', 'S1 Teknologi Informasi', '2022', 'FIT', '1303220008'],
            ['Sie Publikasi', 'Doni Saputra', 'S1 Informatika', '2023', 'FIT', '1301230021'],
            ['Sie Publikasi', 'Indah Permatasari', 'S1 Sistem Informasi', '2023', 'FIT', '1301230045'],
            ['Sie Keamanan', 'Arif Wicaksono', 'S1 Teknik Elektro', '2022', 'FTE', '1302220034'],
            ['Sie Humas', 'Laila Fitriani', 'S1 Informatika', '2022', 'FIT', '1301220147'],
            ['Sie Humas', 'Gilang Ramadhan', 'S1 Sistem Informasi', '2023', 'FIT', '1301230067'],
            ['Anggota', 'Rini Susanti', 'S1 Informatika', '2023', 'FIT', '1301230089'],
            ['Anggota', 'Faisal Akbar', 'S1 Teknologi Informasi', '2023', 'FIT', '1303230015'],
            ['Anggota', 'Putri Rahayu', 'S1 Informatika', '2023', 'FIT', '1301230112'],
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

        // Anggaran: Pemasukan (Rp0 sesuai template asli)
        ProposalBudget::create([
            'proposal_id' => $proposal->id,
            'jenis'       => 'pemasukan',
            'keterangan'  => 'Dana Kegiatan (tidak ada pemasukan)',
            'total'       => 0,
            'urutan'      => 0,
        ]);

        $this->command->info("ProposalSeeder: Proposal 'Orbit Star #1' berhasil dibuat untuk user {$user->email}");
    }
}
