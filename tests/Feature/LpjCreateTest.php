<?php

namespace Tests\Feature;

use App\Models\Lpj;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LpjCreateTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $override = []): array
    {
        return array_merge([
            'nama_kegiatan'         => 'Workshop IoT untuk Pemula',
            'akronim'               => 'WIOT',
            'tema_kegiatan'         => 'Belajar bersama',
            'tanggal_mulai'         => '2026-06-10',
            'tanggal_selesai'       => '2026-06-10',
            'tempat_kegiatan'       => 'Gedung Tokong Nanas',
            'kota'                  => 'BANDUNG',
            'tahun'                 => 2026,
            'penyelenggara'         => 'SRE Telkom University',
            'latar_belakang'        => '<p>Latar <strong>belakang</strong> kegiatan.</p><ul><li>Poin satu</li></ul>',
            'tujuan_kegiatan'       => ['Meningkatkan kompetensi'],
            'sasaran_kegiatan'      => 'Mahasiswa baru',
            'bentuk_kegiatan'       => 'Workshop',
            'deskripsi_pelaksanaan' => 'Kegiatan berjalan lancar.',
            'simpulan_rekomendasi'  => 'Kegiatan sukses.',
            'penutup'               => 'Terima kasih.',
            'ketua_pelaksana_nama'  => 'Budi',
            'ketua_pelaksana_nim'   => '10312345',
            'ketua_ukm_nama'        => 'Andi',
            'ketua_ukm_nim'         => '10398765',
            'pembina_1_nama'        => 'Dr. Pembina',
            'pembina_1_nip'         => '20810001',
            'dana_masuk'            => [
                ['sumber_dana' => 'DIKMA', 'target' => 5000000, 'jumlah_total' => 4970000],
            ],
            'risks' => [
                [
                    'uraian_kegiatan'     => 'Praktik perangkat',
                    'identifikasi_bahaya' => 'Kerusakan modul sensor',
                    'peluang'             => '3/5',
                    'akibat'              => '3/5',
                    'tingkat_risiko'      => '2/5',
                    'pengendalian_risiko' => 'Sediakan modul cadangan',
                    'penanggung_jawab'    => 'Sie Perlengkapan',
                ],
            ],
        ], $override);
    }

    public function test_create_persists_lpj_with_rich_text_and_dana_masuk(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('lpj.store'), $this->validPayload());

        $lpj = Lpj::where('nama_kegiatan', 'Workshop IoT untuk Pemula')->first();
        $this->assertNotNull($lpj);
        $response->assertRedirect(route('lpj.show', $lpj));

        // Rich text disimpan sebagai HTML (issue 5).
        $this->assertStringContainsString('<strong>belakang</strong>', $lpj->latar_belakang);

        // Analisis Risiko: Identifikasi Bahaya = teks, Peluang & Akibat = X/5,
        // Tingkat Risiko dipetakan server ke kategori matrix Tel-U: 3 x 3 = 9 -> MEDIUM.
        $this->assertDatabaseHas('lpj_risks', [
            'lpj_id'              => $lpj->id,
            'identifikasi_bahaya' => 'Kerusakan modul sensor',
            'peluang'             => '3/5',
            'akibat'              => '3/5',
            'tingkat_risiko'      => 'MEDIUM',
        ]);

        // Dana masuk tersimpan dengan kolom Jumlah/Total (issue 6).
        $this->assertDatabaseHas('lpj_budgets', [
            'lpj_id'       => $lpj->id,
            'jenis'        => 'dana_masuk',
            'sumber_dana'  => 'DIKMA',
            'jumlah_total' => 4970000,
        ]);
    }

    public function test_create_stores_collab_logos_and_merged_lampiran_pdf(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $payload = $this->validPayload([
            'logo_organisasi' => UploadedFile::fake()->image('main.png', 200, 120),
            'logo_kolaborasi' => [
                UploadedFile::fake()->image('collab1.png', 200, 120),
                UploadedFile::fake()->image('collab2.png', 200, 120),
            ],
            'lampiran_pdf' => UploadedFile::fake()->create('lampiran.pdf', 100, 'application/pdf'),
        ]);

        $this->actingAs($user)->post(route('lpj.store'), $payload);

        $lpj = Lpj::where('nama_kegiatan', 'Workshop IoT untuk Pemula')->firstOrFail();

        // Issue 4: dua logo kolaborasi tersimpan.
        $this->assertEquals(2, $lpj->collabLogos()->count());

        // Issue 7: satu lampiran PDF tersimpan.
        $this->assertEquals(1, $lpj->attachments()->where('jenis', 'lampiran')->count());
    }

    public function test_create_requires_main_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('lpj.create'))
            ->post(route('lpj.store'), [])
            ->assertSessionHasErrors(['nama_kegiatan', 'latar_belakang', 'penyelenggara']);
    }
}
