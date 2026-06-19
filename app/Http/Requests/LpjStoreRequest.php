<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LpjStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan'         => 'required|string|max:255',
            'akronim'               => 'nullable|string|max:255',
            'tema_kegiatan'         => 'nullable|string|max:255',
            'tanggal_mulai'         => 'required|date',
            'tanggal_selesai'       => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'           => 'nullable|date_format:H:i',
            'waktu_selesai'         => 'nullable|date_format:H:i',
            'tempat_kegiatan'       => 'nullable|string|max:255',
            'kota'                  => 'nullable|string|max:255',
            'tahun'                 => 'required|integer|min:2000|max:2099',
            'penyelenggara'         => 'required|string|max:255',
            'afiliasi'              => 'nullable|string|max:255',
            'latar_belakang'        => 'required|string',
            'tujuan_kegiatan'       => 'required|array|min:1',
            'tujuan_kegiatan.*'     => 'nullable|string',
            'sasaran_kegiatan'      => 'required|string',
            'bentuk_kegiatan'       => 'required|string',
            'deskripsi_pelaksanaan' => 'required|string',
            'simpulan_rekomendasi'  => 'required|string',
            'penutup'               => 'required|string',

            'ketua_pelaksana_nama'  => 'required|string|max:255',
            'ketua_pelaksana_nim'   => 'required|digits_between:8,20',
            'ketua_ukm_nama'        => 'required|string|max:255',
            'ketua_ukm_nim'         => 'required|digits_between:8,20',
            'pembina_1_nama'        => 'required|string|max:255',
            'pembina_1_nip'         => 'required|digits_between:8,20',
            'pembina_2_nama'        => 'nullable|string|max:255',
            'pembina_2_nip'         => 'nullable|digits_between:8,20',

            'logo_organisasi'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            // Logo UKM kolaborator (opsional, boleh lebih dari satu) - item #4.
            'logo_kolaborasi'       => 'nullable|array|max:10',
            'logo_kolaborasi.*'     => 'nullable|image|mimes:png,jpg,jpeg|max:2048',

            // Q. Lampiran: satu berkas PDF gabungan yang di-merge ke hasil generate - item #7.
            'lampiran_pdf'          => 'nullable|file|mimes:pdf|max:51200',

            // K. Monitoring dan Evaluasi (grup tanggal/fase -> banyak item detail).
            // Opsional dan permisif: grup/item kosong dilewati di controller.
            'monitoring_groups'                          => 'nullable|array',
            'monitoring_groups.*.tanggal'                => 'nullable|date',
            'monitoring_groups.*.fase'                   => 'nullable|string|max:255',
            'monitoring_groups.*.items'                  => 'nullable|array',
            'monitoring_groups.*.items.*.detail_kegiatan' => 'nullable|string',
            'monitoring_groups.*.items.*.pic'            => 'nullable|string|max:255',
            'monitoring_groups.*.items.*.keterangan'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required'                       => 'Kolom ini wajib diisi.',
            'nama_kegiatan.required'         => 'Nama kegiatan wajib diisi.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'             => 'Format tanggal tidak valid.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'           => 'Format tanggal tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'waktu_mulai.date_format'        => 'Format waktu tidak valid.',
            'waktu_selesai.date_format'      => 'Format waktu tidak valid.',
            'tahun.required'                 => 'Tahun wajib diisi.',
            'tahun.integer'                  => 'Tahun harus berupa angka.',
            'tahun.min'                      => 'Tahun yang dimasukkan tidak valid.',
            'tahun.max'                      => 'Tahun yang dimasukkan tidak valid.',
            'penyelenggara.required'         => 'Penyelenggara wajib diisi.',
            'latar_belakang.required'        => 'Latar belakang wajib diisi.',
            'tujuan_kegiatan.required'       => 'Tujuan kegiatan wajib diisi minimal satu.',
            'tujuan_kegiatan.min'            => 'Tujuan kegiatan wajib diisi minimal satu.',
            'sasaran_kegiatan.required'      => 'Sasaran kegiatan wajib diisi.',
            'bentuk_kegiatan.required'       => 'Bentuk kegiatan wajib diisi.',
            'deskripsi_pelaksanaan.required' => 'Deskripsi pelaksanaan wajib diisi.',
            'simpulan_rekomendasi.required'  => 'Simpulan dan rekomendasi wajib diisi.',
            'penutup.required'               => 'Penutup wajib diisi.',

            'ketua_pelaksana_nim.digits_between' => 'NIM harus berupa angka dan minimal 8 digit.',
            'ketua_ukm_nim.digits_between'       => 'NIM harus berupa angka dan minimal 8 digit.',
            'pembina_1_nip.digits_between'       => 'NIP harus berupa angka dan minimal 8 digit.',
            'pembina_2_nip.digits_between'       => 'NIP harus berupa angka dan minimal 8 digit.',

            'logo_organisasi.image'  => 'File harus berupa gambar (JPG atau PNG).',
            'logo_organisasi.mimes'  => 'File harus berupa gambar (JPG atau PNG).',
            'logo_organisasi.max'    => 'Ukuran file tidak boleh lebih dari 2 MB.',
            'logo_kolaborasi.*.image' => 'Logo kolaborasi harus berupa gambar (JPG atau PNG).',
            'logo_kolaborasi.*.mimes' => 'Logo kolaborasi harus berupa gambar (JPG atau PNG).',
            'logo_kolaborasi.*.max'   => 'Ukuran setiap logo kolaborasi tidak boleh lebih dari 2 MB.',

            'lampiran_pdf.mimes'     => 'Lampiran harus berupa berkas PDF.',
            'lampiran_pdf.max'       => 'Ukuran lampiran PDF tidak boleh lebih dari 50 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_kegiatan'         => 'nama kegiatan',
            'akronim'               => 'akronim',
            'tema_kegiatan'         => 'tema kegiatan',
            'tanggal_mulai'         => 'tanggal mulai',
            'tanggal_selesai'       => 'tanggal selesai',
            'tempat_kegiatan'       => 'tempat kegiatan',
            'tahun'                 => 'tahun',
            'penyelenggara'         => 'penyelenggara',
            'latar_belakang'        => 'latar belakang',
            'sasaran_kegiatan'      => 'sasaran kegiatan',
            'bentuk_kegiatan'       => 'bentuk kegiatan',
            'deskripsi_pelaksanaan' => 'deskripsi pelaksanaan',
            'simpulan_rekomendasi'  => 'simpulan dan rekomendasi',
            'penutup'               => 'penutup',
            'ketua_pelaksana_nama'  => 'nama Ketua Pelaksana',
            'ketua_pelaksana_nim'   => 'NIM Ketua Pelaksana',
            'ketua_ukm_nama'        => 'nama Ketua UKM',
            'ketua_ukm_nim'         => 'NIM Ketua UKM',
            'pembina_1_nama'        => 'nama Pembina 1',
            'pembina_1_nip'         => 'NIP Pembina 1',
            'pembina_2_nama'        => 'nama Pembina 2',
            'pembina_2_nip'         => 'NIP Pembina 2',
            'logo_organisasi'       => 'logo organisasi',
        ];
    }
}
