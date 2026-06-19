<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProposalStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan'        => 'required|string|max:255',
            'tema_kegiatan'        => 'nullable|string|max:255',
            'penyelenggara'        => 'nullable|string|max:255',
            'afiliasi'             => 'nullable|string|max:255',
            'tanggal_mulai'        => 'required|date',
            'tanggal_selesai'      => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'          => 'nullable|date_format:H:i',
            'waktu_selesai'        => 'nullable|date_format:H:i',
            'tempat_kegiatan'      => 'nullable|string|max:255',
            'kota'                 => 'nullable|string|max:255',
            'tahun'                => 'required|integer|min:2000|max:2099',
            'latar_belakang'       => 'nullable|string',
            'tujuan_kegiatan'      => 'nullable|array',
            'tujuan_kegiatan.*'    => 'nullable|string',
            'sasaran_kegiatan'     => 'nullable|string',
            'bentuk_kegiatan'      => 'nullable|string',
            'narasumber_kegiatan'  => 'nullable|string',
            'monitoring_evaluasi'  => 'nullable|string',
            'penutup'              => 'nullable|string',

            'president_ukm_nama'   => 'nullable|string|max:255',
            'president_ukm_nim'    => 'nullable|digits_between:8,20',
            'sekretaris_nama'      => 'nullable|string|max:255',
            'sekretaris_nim'       => 'nullable|digits_between:8,20',
            'ketua_pelaksana_nama' => 'nullable|string|max:255',
            'ketua_pelaksana_nim'  => 'nullable|digits_between:8,20',
            'pembina_nama'         => 'nullable|string|max:255',
            'pembina_nip'          => 'nullable|digits_between:8,20',
            'pembina_2_nama'       => 'nullable|string|max:255',
            'pembina_2_nip'        => 'nullable|digits_between:8,20',

            'logo_organisasi'      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            // Logo UKM kolaborator (opsional, boleh lebih dari satu) - item #4.
            'logo_kolaborasi'      => 'nullable|array|max:10',
            'logo_kolaborasi.*'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            // Lampiran kini satu berkas PDF gabungan yang di-merge ke hasil generate - item #7.
            'lampiran_pdf'         => 'nullable|file|mimes:pdf|max:51200',
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

            'president_ukm_nim.digits_between'    => 'NIM harus berupa angka dan minimal 8 digit.',
            'sekretaris_nim.digits_between'       => 'NIM harus berupa angka dan minimal 8 digit.',
            'ketua_pelaksana_nim.digits_between'  => 'NIM harus berupa angka dan minimal 8 digit.',
            'pembina_nip.digits_between'          => 'NIP harus berupa angka dan minimal 8 digit.',
            'pembina_2_nip.digits_between'        => 'NIP harus berupa angka dan minimal 8 digit.',

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
            'nama_kegiatan'        => 'nama kegiatan',
            'tema_kegiatan'        => 'tema kegiatan',
            'penyelenggara'        => 'penyelenggara',
            'afiliasi'             => 'afiliasi',
            'tanggal_mulai'        => 'tanggal mulai',
            'tanggal_selesai'      => 'tanggal selesai',
            'tempat_kegiatan'      => 'tempat kegiatan',
            'tahun'                => 'tahun',
            'latar_belakang'       => 'latar belakang',
            'sasaran_kegiatan'     => 'sasaran kegiatan',
            'bentuk_kegiatan'      => 'bentuk kegiatan',
            'penutup'              => 'penutup',
            'president_ukm_nim'    => 'NIM Presiden UKM',
            'sekretaris_nim'       => 'NIM Sekretaris',
            'ketua_pelaksana_nim'  => 'NIM Ketua Pelaksana',
            'pembina_nip'          => 'NIP Pembina I',
            'pembina_2_nip'        => 'NIP Pembina II',
            'logo_organisasi'      => 'logo organisasi',
        ];
    }
}
