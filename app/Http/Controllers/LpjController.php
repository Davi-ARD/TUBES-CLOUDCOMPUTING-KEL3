<?php

namespace App\Http\Controllers;

use App\Http\Requests\LpjStoreRequest;
use App\Models\Lpj;
use App\Models\LpjRundown;
use App\Models\LpjRisk;
use App\Models\LpjCommittee;
use App\Models\LpjBudget;
use App\Models\LpjAttachment;
use App\Models\LpjDanaKeluarDivision;
use App\Models\LpjDanaKeluarCategory;
use App\Models\LpjDanaKeluarSubitem;
use App\Services\PdfMergeService;
use App\Support\RichText;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LpjController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $lpjs = Lpj::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('lpj.index', compact('lpjs'));
    }

    public function create()
    {
        return view('lpj.create');
    }

    public function store(LpjStoreRequest $request): RedirectResponse
    {
        $lpj = DB::transaction(function () use ($request) {
            $lpj = Lpj::create(array_merge(
                $this->lpjMainData($request),
                [
                    'user_id' => auth()->id(),
                    'status'  => 'draft',
                ]
            ));

            // Logo organisasi
            if ($request->hasFile('logo_organisasi')) {
                $path = $request->file('logo_organisasi')->store('lpj-logos', 'public');
                LpjAttachment::create([
                    'lpj_id'    => $lpj->id,
                    'jenis'     => 'cover_logo',
                    'file_path' => $path,
                    'file_type' => $request->file('logo_organisasi')->getMimeType(),
                    'urutan'    => 0,
                ]);
                $lpj->update(['logo_organisasi_path' => $path]);
            }

            $this->persistChildren($lpj, $request);
            $this->simpanMonitoringGroups($lpj, $request->input('monitoring_groups', []));

            if ($request->filled('dana_keluar_json')) {
                $this->simpanDanaKeluar($lpj, $request->input('dana_keluar_json'));
            }

            $this->simpanLogoKolaborasi($lpj, $request);
            $this->simpanLampiranPdf($lpj, $request);

            return $lpj;
        });

        return redirect()->route('lpj.show', $lpj)
            ->with('success', 'LPJ berhasil disimpan!');
    }

    public function show(Lpj $lpj)
    {
        $this->authorize('view', $lpj);
        $lpj->load(['rundowns', 'risks', 'monitoringGroups.items', 'committees', 'budgets', 'attachments']);

        return view('lpj.show', compact('lpj'));
    }

    public function edit(Lpj $lpj)
    {
        $this->authorize('update', $lpj);
        $lpj->load([
            'rundowns', 'risks', 'monitoringGroups.items', 'committees', 'budgets',
            'danaKeluarDivisions.categories.subitems',
        ]);

        $time = fn ($t) => $t ? Carbon::parse($t)->format('H:i') : '';

        $seed = [
            'data' => [
                'nama_kegiatan'         => $lpj->nama_kegiatan,
                'akronim'               => $lpj->akronim,
                'tema_kegiatan'         => $lpj->tema_kegiatan,
                'penyelenggara'         => $lpj->penyelenggara,
                'afiliasi'              => $lpj->afiliasi,
                'tanggal_mulai'         => optional($lpj->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai'       => optional($lpj->tanggal_selesai)->format('Y-m-d'),
                'waktu_mulai'           => $time($lpj->waktu_mulai),
                'waktu_selesai'         => $time($lpj->waktu_selesai),
                'tempat_kegiatan'       => $lpj->tempat_kegiatan,
                'kota'                  => $lpj->kota,
                'tahun'                 => $lpj->tahun,
                'latar_belakang'        => $lpj->latar_belakang,
                'tujuan_kegiatan'       => array_values($lpj->tujuan_kegiatan ?? []),
                'sasaran_kegiatan'      => $lpj->sasaran_kegiatan,
                'bentuk_kegiatan'       => $lpj->bentuk_kegiatan,
                'deskripsi_pelaksanaan' => $lpj->deskripsi_pelaksanaan,
                'simpulan_rekomendasi'  => $lpj->simpulan_rekomendasi,
                'penutup'               => $lpj->penutup,
                'ketua_pelaksana_nama'  => $lpj->ketua_pelaksana_nama,
                'ketua_pelaksana_nim'   => $lpj->ketua_pelaksana_nim,
                'ketua_ukm_nama'        => $lpj->ketua_ukm_nama,
                'ketua_ukm_nim'         => $lpj->ketua_ukm_nim,
                'pembina_1_nama'        => $lpj->pembina_1_nama,
                'pembina_1_nip'         => $lpj->pembina_1_nip,
                'pembina_2_nama'        => $lpj->pembina_2_nama,
                'pembina_2_nip'         => $lpj->pembina_2_nip,
                'direktur_nama'         => $lpj->direktur_nama,
                'direktur_nip'          => $lpj->direktur_nip,
                'rundowns' => $lpj->rundowns->map(fn ($r) => [
                    'waktu_mulai'     => $time($r->waktu_mulai),
                    'waktu_selesai'   => $time($r->waktu_selesai),
                    'durasi'          => $r->durasi,
                    'detail_kegiatan' => $r->detail_kegiatan,
                    'pic'             => $r->pic,
                ])->values()->all(),
                'risks' => $lpj->risks->map(fn ($r) => [
                    'uraian_kegiatan'     => $r->uraian_kegiatan,
                    'identifikasi_bahaya' => $r->identifikasi_bahaya,
                    'peluang'             => $r->peluang,
                    'akibat'              => $r->akibat,
                    'tingkat_risiko'      => $r->tingkat_risiko,
                    'pengendalian_risiko' => $r->pengendalian_risiko,
                    'penanggung_jawab'    => $r->penanggung_jawab,
                ])->values()->all(),
                'monitoring_groups' => $lpj->monitoringGroups->map(fn ($g) => [
                    'tanggal' => $g->tanggal ? Carbon::parse($g->tanggal)->format('Y-m-d') : '',
                    'fase'    => $g->fase ?? '',
                    'items'   => $g->items->map(fn ($it) => [
                        'detail_kegiatan' => $it->detail_kegiatan,
                        'pic'             => $it->pic,
                        'keterangan'      => $it->keterangan,
                    ])->values()->all(),
                ])->values()->all(),
                'committees' => $lpj->committees->map(fn ($c) => [
                    'jabatan'  => $c->jabatan,
                    'nama'     => $c->nama,
                    'nim'      => $c->nim,
                    'jurusan'  => $c->jurusan,
                    'angkatan' => $c->angkatan,
                    'fakultas' => $c->fakultas,
                ])->values()->all(),
                'dana_masuk' => $lpj->budgets->where('jenis', 'dana_masuk')->map(fn ($b) => [
                    'sumber_dana'  => $b->sumber_dana,
                    'target'       => (float) $b->target,
                    'jumlah_total' => (float) $b->jumlah_total,
                ])->values()->all(),
            ],
            'logoUrl' => $lpj->logo_organisasi_path ? Storage::url($lpj->logo_organisasi_path) : null,
        ];

        $danaKeluarSeed = $lpj->danaKeluarDivisions->map(fn ($d) => [
            'nama_divisi' => $d->nama_divisi,
            'categories'  => $d->categories->map(fn ($k) => [
                'nama_kategori' => $k->nama_kategori,
                'subitems'      => $k->subitems->map(fn ($s) => [
                    'rincian_kebutuhan' => $s->rincian_kebutuhan,
                    'jumlah'            => (float) $s->jumlah,
                    'satuan'            => $s->satuan,
                    'harga_satuan'      => (float) $s->harga_satuan,
                ])->values()->all(),
            ])->values()->all(),
        ])->values()->all();

        return view('lpj.edit', compact('lpj', 'seed', 'danaKeluarSeed'));
    }

    public function update(LpjStoreRequest $request, Lpj $lpj): RedirectResponse
    {
        $this->authorize('update', $lpj);

        DB::transaction(function () use ($request, $lpj) {
            // Update kolom utama. Edit mengembalikan status ke draft (PDF lama jadi usang).
            $lpj->update(array_merge(
                $this->lpjMainData($request),
                ['status' => 'draft', 'generated_at' => null]
            ));

            // Logo: ganti hanya bila ada upload baru.
            if ($request->hasFile('logo_organisasi')) {
                if ($lpj->logo_organisasi_path) {
                    Storage::disk('public')->delete($lpj->logo_organisasi_path);
                }
                $lpj->attachments()->where('jenis', 'cover_logo')->delete();

                $path = $request->file('logo_organisasi')->store('lpj-logos', 'public');
                LpjAttachment::create([
                    'lpj_id'    => $lpj->id,
                    'jenis'     => 'cover_logo',
                    'file_path' => $path,
                    'file_type' => $request->file('logo_organisasi')->getMimeType(),
                    'urutan'    => 0,
                ]);
                $lpj->update(['logo_organisasi_path' => $path]);
            }

            // Sinkronisasi tabel anak (hapus lama, buat ulang).
            $this->persistChildren($lpj, $request);
            $this->simpanMonitoringGroups($lpj, $request->input('monitoring_groups', []));
            $this->simpanDanaKeluar($lpj, $request->input('dana_keluar_json', '[]'));

            // Lampiran & logo kolaborasi: hapus yang dicentang, lalu tambahkan upload baru.
            foreach ((array) $request->input('hapus_lampiran', []) as $attId) {
                $att = $lpj->attachments()->whereKey($attId)->first();
                if ($att) {
                    Storage::disk('public')->delete($att->file_path);
                    $att->delete();
                }
            }
            $this->simpanLogoKolaborasi($lpj, $request);
            $this->simpanLampiranPdf($lpj, $request);
        });

        return redirect()->route('lpj.show', $lpj)
            ->with('success', 'LPJ berhasil diperbarui! Status kembali menjadi Draf, generate ulang PDF untuk versi terbaru.');
    }

    public function generatePdf(Lpj $lpj)
    {
        $this->authorize('view', $lpj);
        $lpj->load(['rundowns', 'risks', 'monitoringGroups.items', 'committees', 'budgets', 'attachments', 'collabLogos', 'danaKeluarDivisions.categories.subitems']);

        $pdf = Pdf::loadView('lpj.pdf.lpj', compact('lpj'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'         => true,
                'defaultFont'          => 'dejavu serif',
                'dpi'                  => 96,
                'chroot'               => base_path(),
            ]);

        $lpj->update(['status' => 'generated', 'generated_at' => now()]);

        $filename = 'LPJ - ' . $lpj->nama_kegiatan . '.pdf';

        // Lampiran ber-mime PDF tidak bisa di-embed dompdf; gabungkan di akhir dokumen.
        $pdfLampiran = $lpj->attachments
            ->whereNotIn('jenis', ['cover_logo', 'collab_logo'])
            ->filter(fn ($a) => str_contains((string) $a->file_type, 'pdf')
                || preg_match('/\.pdf$/i', $a->file_path))
            ->sortBy('urutan')
            ->map(fn ($a) => storage_path('app/public/' . $a->file_path))
            ->values()
            ->all();

        if (empty($pdfLampiran)) {
            return $pdf->download($filename);
        }

        $merged = app(PdfMergeService::class)->mergeAppend($pdf->output(), $pdfLampiran);

        return response($merged, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . addslashes($filename) . '"',
        ]);
    }

    public function saveDanaKeluar(Request $request, Lpj $lpj): RedirectResponse
    {
        $this->authorize('update', $lpj);

        $request->validate(['dana_keluar_json' => 'required|string']);

        $this->simpanDanaKeluar($lpj, $request->input('dana_keluar_json'));

        return back()->with('success', 'Dana keluar berhasil disimpan.');
    }

    public function destroy(Lpj $lpj): RedirectResponse
    {
        $this->authorize('delete', $lpj);

        if ($lpj->logo_organisasi_path) {
            Storage::disk('public')->delete($lpj->logo_organisasi_path);
        }

        foreach ($lpj->attachments as $att) {
            Storage::disk('public')->delete($att->file_path);
        }

        $lpj->delete();

        return redirect()->route('lpj.index')
            ->with('success', 'LPJ berhasil dihapus.');
    }

    /**
     * Susun data kolom utama LPJ dari request (dipakai store & update).
     */
    private function lpjMainData(Request $request): array
    {
        $tujuan = array_values(array_filter(
            $request->input('tujuan_kegiatan', []),
            fn ($v) => trim((string) $v) !== ''
        ));

        return [
            'nama_kegiatan'         => $request->nama_kegiatan,
            'akronim'               => $request->akronim,
            'tema_kegiatan'         => $request->tema_kegiatan,
            'tanggal_mulai'         => $request->tanggal_mulai,
            'tanggal_selesai'       => $request->tanggal_selesai,
            'waktu_mulai'           => $request->waktu_mulai ?: null,
            'waktu_selesai'         => $request->waktu_selesai ?: null,
            'tempat_kegiatan'       => $request->tempat_kegiatan,
            'kota'                  => $request->kota ?: 'BANDUNG',
            'tahun'                 => $request->tahun,
            'penyelenggara'         => $request->penyelenggara,
            'afiliasi'              => $request->afiliasi,
            'latar_belakang'        => RichText::sanitize($request->latar_belakang),
            'tujuan_kegiatan'       => $tujuan,
            'sasaran_kegiatan'      => RichText::sanitize($request->sasaran_kegiatan),
            'bentuk_kegiatan'       => RichText::sanitize($request->bentuk_kegiatan),
            'deskripsi_pelaksanaan' => RichText::sanitize($request->deskripsi_pelaksanaan),
            'simpulan_rekomendasi'  => RichText::sanitize($request->simpulan_rekomendasi),
            'penutup'               => RichText::sanitize($request->penutup),
            'ketua_pelaksana_nama'  => $request->ketua_pelaksana_nama,
            'ketua_pelaksana_nim'   => $request->ketua_pelaksana_nim,
            'ketua_ukm_nama'        => $request->ketua_ukm_nama,
            'ketua_ukm_nim'         => $request->ketua_ukm_nim,
            'pembina_1_nama'        => $request->pembina_1_nama,
            'pembina_1_nip'         => $request->pembina_1_nip,
            'pembina_2_nama'        => $request->pembina_2_nama,
            'pembina_2_nip'         => $request->pembina_2_nip,
            'direktur_nama'         => $request->direktur_nama ?: 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'          => $request->direktur_nip ?: '20820005',
        ];
    }

    /**
     * Sinkronisasi rundown, risiko, kepanitiaan, dan dana masuk.
     * Strategi: hapus baris lama lalu buat ulang dari input.
     */
    private function persistChildren(Lpj $lpj, Request $request): void
    {
        $lpj->rundowns()->delete();
        $lpj->risks()->delete();
        $lpj->committees()->delete();
        $lpj->budgets()->where('jenis', 'dana_masuk')->delete();

        // Rundown
        foreach ($request->input('rundowns', []) as $i => $row) {
            if (empty($row['detail_kegiatan'])) continue;
            LpjRundown::create([
                'lpj_id'          => $lpj->id,
                'urutan'          => $i,
                'waktu_mulai'     => $row['waktu_mulai'] ?? '',
                'waktu_selesai'   => $row['waktu_selesai'] ?? '',
                'durasi'          => $row['durasi'] ?? '',
                'detail_kegiatan' => $row['detail_kegiatan'],
                'pic'             => $row['pic'] ?? '',
            ]);
        }

        // Risiko: Tingkat Risiko dihitung ulang di server dari Peluang x Akibat
        // dan dipetakan ke kategori matrix grading Tel-U (LOW/MEDIUM/HIGH/DANGER).
        foreach ($request->input('risks', []) as $i => $row) {
            if (empty($row['uraian_kegiatan'])) continue;

            LpjRisk::create([
                'lpj_id'              => $lpj->id,
                'urutan'             => $i,
                'uraian_kegiatan'    => $row['uraian_kegiatan'],
                'identifikasi_bahaya' => $row['identifikasi_bahaya'] ?? '',
                'peluang'            => $row['peluang'] ?? '',
                'akibat'             => $row['akibat'] ?? '',
                'tingkat_risiko'     => \App\Support\RiskMatrix::grade($row['peluang'] ?? null, $row['akibat'] ?? null),
                'pengendalian_risiko' => $row['pengendalian_risiko'] ?? '',
                'penanggung_jawab'   => $row['penanggung_jawab'] ?? '',
            ]);
        }

        // Kepanitiaan
        foreach ($request->input('committees', []) as $i => $row) {
            if (empty($row['nama'])) continue;
            LpjCommittee::create([
                'lpj_id'   => $lpj->id,
                'urutan'   => $i,
                'jabatan'  => $row['jabatan'] ?? '',
                'nama'     => $row['nama'],
                'nim'      => $row['nim'] ?? '',
                'jurusan'  => $row['jurusan'] ?? '',
                'fakultas' => $row['fakultas'] ?? '',
                'angkatan' => $row['angkatan'] ?? '',
            ]);
        }

        // Dana Masuk
        foreach ($request->input('dana_masuk', []) as $i => $row) {
            if (empty($row['sumber_dana'])) continue;
            LpjBudget::create([
                'lpj_id'       => $lpj->id,
                'jenis'        => 'dana_masuk',
                'urutan'       => $i,
                'sumber_dana'  => $row['sumber_dana'],
                'target'       => (float) ($row['target'] ?? 0),
                'jumlah_total' => (float) ($row['jumlah_total'] ?? 0),
            ]);
        }
    }

    /**
     * Simpan satu berkas PDF lampiran (item #7). Lampiran kini tunggal: upload
     * baru menggantikan lampiran lama. Tanpa upload baru, lampiran lama tetap
     * (kecuali dihapus eksplisit lewat hapus_lampiran).
     */
    private function simpanLampiranPdf(Lpj $lpj, Request $request): void
    {
        if (!$request->hasFile('lampiran_pdf')) return;

        // Ganti: buang lampiran lama (jenis lampiran/nota/dll) lebih dulu.
        foreach ($lpj->lampiranAttachments()->get() as $old) {
            Storage::disk('public')->delete($old->file_path);
            $old->delete();
        }

        $file = $request->file('lampiran_pdf');
        $path = $file->store("lpj-attachments/{$lpj->id}", 'public');
        LpjAttachment::create([
            'lpj_id'    => $lpj->id,
            'jenis'     => 'lampiran',
            'caption'   => 'Lampiran',
            'file_path' => $path,
            'file_type' => $file->getMimeType() ?: 'application/pdf',
            'urutan'    => 900,
        ]);
    }

    /**
     * Tambahkan logo UKM kolaborator (item #4) - append, tidak menghapus yang lama.
     */
    private function simpanLogoKolaborasi(Lpj $lpj, Request $request): void
    {
        if (!$request->hasFile('logo_kolaborasi')) return;

        $base = (int) $lpj->collabLogos()->max('urutan');
        foreach ($request->file('logo_kolaborasi') as $i => $file) {
            if (!$file || !$file->isValid()) continue;
            $path = $file->store('lpj-logos/collab', 'public');
            LpjAttachment::create([
                'lpj_id'    => $lpj->id,
                'jenis'     => 'collab_logo',
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'urutan'    => $base + $i + 1,
            ]);
        }
    }

    /**
     * Simpan section K. Monitoring dan Evaluasi.
     * Struktur: setiap grup (tanggal + fase) memiliki banyak item detail kegiatan.
     */
    private function simpanMonitoringGroups(Lpj $lpj, array $groups): void
    {
        $lpj->monitoringGroups()->delete();

        foreach (array_values($groups) as $gIdx => $groupData) {
            $items = array_values(array_filter(
                $groupData['items'] ?? [],
                fn ($item) => trim($item['detail_kegiatan'] ?? '') !== ''
            ));

            if ($items === []
                && trim($groupData['tanggal'] ?? '') === ''
                && trim($groupData['fase'] ?? '') === '') {
                continue;
            }

            $group = $lpj->monitoringGroups()->create([
                'urutan'  => $gIdx,
                'tanggal' => ($groupData['tanggal'] ?? '') ?: null,
                'fase'    => ($groupData['fase'] ?? '') ?: null,
            ]);

            foreach ($items as $iIdx => $item) {
                $group->items()->create([
                    'urutan'          => $iIdx,
                    'detail_kegiatan' => $item['detail_kegiatan'],
                    'pic'             => $item['pic'] ?? '',
                    'keterangan'      => $item['keterangan'] ?? '',
                ]);
            }
        }
    }

    private function simpanDanaKeluar(Lpj $lpj, string $danaKeluarJson): void
    {
        $lpj->danaKeluarDivisions()->delete();

        $data = json_decode($danaKeluarJson, true);
        if (!$data || !is_array($data)) return;

        foreach ($data as $dIdx => $divisiData) {
            if (empty(trim($divisiData['nama_divisi'] ?? ''))) continue;

            $divisi = $lpj->danaKeluarDivisions()->create([
                'nama_divisi' => trim($divisiData['nama_divisi']),
                'urutan'      => $dIdx,
            ]);

            foreach ($divisiData['categories'] ?? [] as $kIdx => $katData) {
                if (empty(trim($katData['nama_kategori'] ?? ''))) continue;

                $kategori = $divisi->categories()->create([
                    'nama_kategori' => trim($katData['nama_kategori']),
                    'nomor'         => $kIdx + 1,
                    'urutan'        => $kIdx,
                ]);

                foreach ($katData['subitems'] ?? [] as $sIdx => $subData) {
                    if (empty(trim($subData['rincian_kebutuhan'] ?? ''))) continue;

                    $kategori->subitems()->create([
                        'rincian_kebutuhan' => trim($subData['rincian_kebutuhan']),
                        'jumlah'            => (float) ($subData['jumlah'] ?? 0),
                        'satuan'            => trim($subData['satuan'] ?? ''),
                        'harga_satuan'      => (float) ($subData['harga_satuan'] ?? 0),
                        'urutan'            => $sIdx,
                    ]);
                }
            }
        }
    }
}
