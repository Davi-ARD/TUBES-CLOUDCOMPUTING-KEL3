<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LpjFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'               => \App\Models\User::factory(),
            'nama_kegiatan'         => $this->faker->sentence(4),
            'tema_kegiatan'         => $this->faker->sentence(5),
            'tanggal_mulai'         => '2026-01-01',
            'tanggal_selesai'       => '2026-01-02',
            'tahun'                 => 2026,
            'tempat_kegiatan'       => 'Aula Utama',
            'penyelenggara'         => 'UKM Test',
            'latar_belakang'        => $this->faker->paragraph(),
            'tujuan_kegiatan'       => ['Tujuan 1'],
            'sasaran_kegiatan'      => $this->faker->sentence(),
            'bentuk_kegiatan'       => $this->faker->sentence(),
            'deskripsi_pelaksanaan' => $this->faker->paragraph(),
            'simpulan_rekomendasi'  => $this->faker->paragraph(),
            'penutup'               => $this->faker->paragraph(),
            'ketua_pelaksana_nama'  => $this->faker->name(),
            'ketua_pelaksana_nim'   => '1234567890',
            'ketua_ukm_nama'        => $this->faker->name(),
            'ketua_ukm_nim'         => '0987654321',
            'pembina_1_nama'        => $this->faker->name(),
            'pembina_1_nip'         => '20820001',
            'direktur_nama'         => 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'          => '20820005',
            'status'                => 'draft',
        ];
    }
}
