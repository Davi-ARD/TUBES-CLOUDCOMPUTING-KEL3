<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalStoreRequest;
use App\Models\Proposal;
use App\Models\ProposalRundown;
use App\Models\ProposalRisk;
use App\Models\ProposalCommittee;
use App\Models\ProposalBudget;
use App\Models\ProposalAttachment;
use App\Services\PdfMergeService;
use App\Support\RichText;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $proposals = Proposal::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('proposal.index', compact('proposals'));
    }

    public function create()
    {
        return view('proposal.create');
    }

    public function store(ProposalStoreRequest $request): RedirectResponse
    {
        $proposal = DB::transaction(function () use ($request) {
            // Handle logo organisasi upload
            $logoPath = null;
            if ($request->hasFile('logo_organisasi')) {
                $logoPath = $request->file('logo_organisasi')->store('proposal-logos', 'public');
            }

            $proposal = Proposal::create(array_merge(
                $this->mainData($request),
                [
                    'user_id'              => auth()->id(),
                    'logo_organisasi_path' => $logoPath,
                    'status'               => 'draft',
                ]
            ));

            $totalPengeluaran = $this->persistChildren($proposal, $request);
            $proposal->update(['total_anggaran' => $totalPengeluaran]);

            $this->storeCollabLogos($proposal, $request);
            $this->storeLampiranPdf($proposal, $request);

            return $proposal;
        });

        return redirect()->route('proposal.show', $proposal)
            ->with('success', 'Proposal berhasil disimpan!');
    }

    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);
        $proposal->load(['rundowns', 'risks', 'committees', 'budgets', 'attachments']);

        return view('proposal.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        $this->authorize('update', $proposal);
        $proposal->load(['rundowns', 'risks', 'committees', 'budgets', 'lampiranAttachments']);

        $time = fn ($t) => $t ? Carbon::parse($t)->format('H:i') : '';

        $seed = [
            'data' => [
                'nama_kegiatan'       => $proposal->nama_kegiatan,
                'tema_kegiatan'       => $proposal->tema_kegiatan,
                'penyelenggara'       => $proposal->penyelenggara,
                'afiliasi'            => $proposal->afiliasi,
                'tanggal_mulai'       => optional($proposal->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai'     => optional($proposal->tanggal_selesai)->format('Y-m-d'),
                'waktu_mulai'         => $time($proposal->waktu_mulai),
                'waktu_selesai'       => $time($proposal->waktu_selesai),
                'tempat_kegiatan'     => $proposal->tempat_kegiatan,
                'kota'                => $proposal->kota,
                'tahun'               => $proposal->tahun,
                'latar_belakang'      => $proposal->latar_belakang,
                'sasaran_kegiatan'    => $proposal->sasaran_kegiatan,
                'bentuk_kegiatan'     => $proposal->bentuk_kegiatan,
                'narasumber_kegiatan' => $proposal->narasumber_kegiatan,
                'monitoring_evaluasi' => $proposal->monitoring_evaluasi,
                'penutup'             => $proposal->penutup,
            ],
            'tujuanList' => array_values($proposal->tujuan_kegiatan ?? []),
            'materiList' => array_values(array_map(fn ($m) => [
                'judul'     => $m['judul'] ?? '',
                'deskripsi' => $m['deskripsi'] ?? '',
            ], $proposal->materi_kegiatan ?? [])),
            'rundowns' => $proposal->rundowns->map(fn ($r) => [
                'waktu_mulai'   => $time($r->waktu_mulai),
                'waktu_selesai' => $time($r->waktu_selesai),
                'durasi_menit'  => $r->durasi_menit,
                'aktivitas'     => $r->aktivitas,
            ])->values()->all(),
            'risks' => $proposal->risks->map(fn ($r) => [
                'uraian_kegiatan'     => $r->uraian_kegiatan,
                'identifikasi_bahaya' => $r->identifikasi_bahaya,
                'peluang'             => $r->peluang,
                'akibat'              => $r->akibat,
                'tingkat_risiko'      => $r->tingkat_risiko,
                'pengendalian_risiko' => $r->pengendalian_risiko,
                'penanggung_jawab'    => $r->penanggung_jawab,
            ])->values()->all(),
            'committees' => $proposal->committees->map(fn ($c) => [
                'jabatan'        => $c->jabatan,
                'nama'           => $c->nama,
                'jurusan'        => $c->jurusan,
                'tahun_angkatan' => $c->tahun_angkatan,
                'fakultas'       => $c->fakultas,
                'nim'            => $c->nim,
            ])->values()->all(),
            'budgetsPemasukan' => $proposal->budgets->where('jenis', 'pemasukan')->map(fn ($b) => [
                'keterangan' => $b->keterangan,
                'total'      => (float) $b->total,
            ])->values()->all(),
            'budgetsPengeluaran' => $proposal->budgets->where('jenis', 'pengeluaran')->map(fn ($b) => [
                'keterangan'   => $b->keterangan,
                'kuantitas'    => (int) $b->kuantitas,
                'satuan'       => $b->satuan,
                'harga_satuan' => (float) $b->harga_satuan,
                'total'        => (float) $b->total,
            ])->values()->all(),
            'budgetsSumberDana' => $proposal->budgets->where('jenis', 'sumber_dana')->map(fn ($b) => [
                'keterangan' => $b->keterangan,
                'total'      => (float) $b->total,
            ])->values()->all(),
            'logoUrl' => $proposal->logo_organisasi_path ? Storage::url($proposal->logo_organisasi_path) : null,
        ];

        return view('proposal.edit', compact('proposal', 'seed'));
    }

    public function update(ProposalStoreRequest $request, Proposal $proposal): RedirectResponse
    {
        $this->authorize('update', $proposal);

        DB::transaction(function () use ($request, $proposal) {
            // Logo: ganti hanya bila ada upload baru, jika tidak pertahankan yang lama.
            $logoPath = $proposal->logo_organisasi_path;
            if ($request->hasFile('logo_organisasi')) {
                if ($logoPath) {
                    Storage::disk('public')->delete($logoPath);
                }
                $logoPath = $request->file('logo_organisasi')->store('proposal-logos', 'public');
            }

            // Update kolom utama. Edit mengembalikan status ke draft (PDF lama jadi usang).
            $proposal->update(array_merge(
                $this->mainData($request),
                [
                    'logo_organisasi_path' => $logoPath,
                    'status'               => 'draft',
                    'generated_at'         => null,
                ]
            ));

            // Sinkronisasi tabel anak: hapus lama, buat ulang dari input.
            $totalPengeluaran = $this->persistChildren($proposal, $request);
            $proposal->update(['total_anggaran' => $totalPengeluaran]);

            // Lampiran & logo kolaborasi: hapus yang dicentang, lalu tambahkan upload baru.
            foreach ((array) $request->input('hapus_lampiran', []) as $attId) {
                $att = $proposal->attachments()->whereKey($attId)->first();
                if ($att) {
                    Storage::disk('public')->delete($att->file_path);
                    $att->delete();
                }
            }
            $this->storeCollabLogos($proposal, $request);
            $this->storeLampiranPdf($proposal, $request);
        });

        return redirect()->route('proposal.show', $proposal)
            ->with('success', 'Proposal berhasil diperbarui! Status kembali menjadi Draf, generate ulang PDF untuk versi terbaru.');
    }

    public function generatePdf(Proposal $proposal)
    {
        $this->authorize('view', $proposal);
        $proposal->load(['rundowns', 'risks', 'committees', 'budgets', 'lampiranAttachments', 'collabLogos']);

        $pdf = Pdf::loadView('proposal.pdf.proposal', compact('proposal'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'         => true,
                'defaultFont'          => 'dejavu serif',
                'dpi'                  => 96,
                'chroot'               => base_path(),
            ]);

        $proposal->update(['status' => 'generated', 'generated_at' => now()]);

        $filename = 'Proposal Kegiatan - ' . $proposal->nama_kegiatan . '.pdf';

        // Lampiran ber-mime PDF tidak bisa di-embed dompdf; gabungkan di akhir dokumen.
        $pdfLampiran = $proposal->lampiranAttachments
            ->filter(fn ($a) => str_contains((string) $a->file_type, 'pdf')
                || preg_match('/\.pdf$/i', $a->file_path))
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

    public function destroy(Proposal $proposal): RedirectResponse
    {
        $this->authorize('delete', $proposal);

        if ($proposal->logo_organisasi_path) {
            Storage::disk('public')->delete($proposal->logo_organisasi_path);
        }

        foreach ($proposal->attachments as $att) {
            Storage::disk('public')->delete($att->file_path);
        }

        $proposal->delete();

        return redirect()->route('proposal.index')
            ->with('success', 'Proposal berhasil dihapus.');
    }

    /**
     * Susun data kolom utama proposal dari request (dipakai store & update).
     */
    private function mainData(Request $request): array
    {
        $tujuan = array_values(array_filter(
            $request->input('tujuan_kegiatan', []),
            fn ($v) => trim((string) $v) !== ''
        ));

        $materi = array_values(array_filter(
            $request->input('materi_kegiatan', []),
            fn ($m) => isset($m['judul']) && trim((string) $m['judul']) !== ''
        ));

        return [
            'nama_kegiatan'        => $request->nama_kegiatan,
            'tema_kegiatan'        => $request->tema_kegiatan,
            'penyelenggara'        => $request->penyelenggara,
            'afiliasi'             => $request->afiliasi,
            'tanggal_mulai'        => $request->tanggal_mulai,
            'tanggal_selesai'      => $request->tanggal_selesai,
            'waktu_mulai'          => $request->waktu_mulai ?: null,
            'waktu_selesai'        => $request->waktu_selesai ?: null,
            'tempat_kegiatan'      => $request->tempat_kegiatan,
            'kota'                 => $request->kota ?: 'BANDUNG',
            'tahun'                => $request->tahun,
            'latar_belakang'       => RichText::sanitize($request->latar_belakang),
            'tujuan_kegiatan'      => $tujuan,
            'sasaran_kegiatan'     => RichText::sanitize($request->sasaran_kegiatan),
            'bentuk_kegiatan'      => RichText::sanitize($request->bentuk_kegiatan),
            'materi_kegiatan'      => $materi,
            'narasumber_kegiatan'  => $request->narasumber_kegiatan,
            'monitoring_evaluasi'  => RichText::sanitize($request->monitoring_evaluasi),
            'penutup'              => RichText::sanitize($request->penutup),
            'president_ukm_nama'   => $request->president_ukm_nama,
            'president_ukm_nim'    => $request->president_ukm_nim,
            'sekretaris_nama'      => $request->sekretaris_nama,
            'sekretaris_nim'       => $request->sekretaris_nim,
            'ketua_pelaksana_nama' => $request->ketua_pelaksana_nama,
            'ketua_pelaksana_nim'  => $request->ketua_pelaksana_nim,
            'pembina_nama'         => $request->pembina_nama,
            'pembina_nip'          => $request->pembina_nip,
            'pembina_2_nama'       => $request->pembina_2_nama,
            'pembina_2_nip'        => $request->pembina_2_nip,
            'direktur_nama'        => $request->direktur_nama ?: 'Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog',
            'direktur_nip'         => $request->direktur_nip ?: '20820005',
        ];
    }

    /**
     * Sinkronisasi tabel anak (rundown, risiko, panitia, anggaran).
     * Strategi: hapus baris lama lalu buat ulang dari input. Mengembalikan total pengeluaran.
     */
    private function persistChildren(Proposal $proposal, Request $request): float
    {
        $proposal->rundowns()->delete();
        $proposal->risks()->delete();
        $proposal->committees()->delete();
        $proposal->budgets()->delete();

        // Rundowns
        foreach ($request->input('rundowns', []) as $i => $row) {
            if (empty($row['aktivitas'])) continue;
            ProposalRundown::create([
                'proposal_id'   => $proposal->id,
                'urutan'        => $i,
                'waktu_mulai'   => $row['waktu_mulai'] ?? '00:00',
                'waktu_selesai' => $row['waktu_selesai'] ?? '00:00',
                'durasi_menit'  => (int) ($row['durasi_menit'] ?? 0),
                'aktivitas'     => $row['aktivitas'],
            ]);
        }

        // Risks: Tingkat Risiko dihitung ulang di server dari Peluang x Akibat
        // dan dipetakan ke kategori matrix grading Tel-U (LOW/MEDIUM/HIGH/DANGER).
        // Identifikasi Bahaya adalah deskripsi (teks).
        foreach ($request->input('risks', []) as $i => $row) {
            if (empty($row['uraian_kegiatan'])) continue;

            ProposalRisk::create([
                'proposal_id'         => $proposal->id,
                'urutan'              => $i,
                'uraian_kegiatan'     => $row['uraian_kegiatan'],
                'identifikasi_bahaya' => $row['identifikasi_bahaya'] ?? null,
                'peluang'             => $row['peluang'] ?? '1/5',
                'akibat'              => $row['akibat'] ?? '1/5',
                'tingkat_risiko'      => \App\Support\RiskMatrix::grade($row['peluang'] ?? null, $row['akibat'] ?? null),
                'pengendalian_risiko' => $row['pengendalian_risiko'] ?? null,
                'penanggung_jawab'    => $row['penanggung_jawab'] ?? null,
            ]);
        }

        // Committees
        foreach ($request->input('committees', []) as $i => $row) {
            if (empty($row['nama'])) continue;
            ProposalCommittee::create([
                'proposal_id'    => $proposal->id,
                'urutan'         => $i,
                'jabatan'        => $row['jabatan'] ?? '',
                'nama'           => $row['nama'],
                'jurusan'        => $row['jurusan'] ?? null,
                'tahun_angkatan' => $row['tahun_angkatan'] ?? null,
                'fakultas'       => $row['fakultas'] ?? null,
                'nim'            => $row['nim'] ?? null,
            ]);
        }

        // Budgets: pemasukan
        foreach ($request->input('budgets_pemasukan', []) as $i => $row) {
            if (empty($row['keterangan'])) continue;
            ProposalBudget::create([
                'proposal_id' => $proposal->id,
                'jenis'       => 'pemasukan',
                'keterangan'  => $row['keterangan'],
                'total'       => (float) ($row['total'] ?? 0),
                'urutan'      => $i,
            ]);
        }

        // Budgets: pengeluaran
        $totalPengeluaran = 0;
        foreach ($request->input('budgets_pengeluaran', []) as $i => $row) {
            if (empty($row['keterangan'])) continue;
            $qty   = (int) ($row['kuantitas'] ?? 0);
            $harga = (float) ($row['harga_satuan'] ?? 0);
            $total = $qty > 0 && $harga > 0 ? $qty * $harga : (float) ($row['total'] ?? 0);
            $totalPengeluaran += $total;
            ProposalBudget::create([
                'proposal_id'  => $proposal->id,
                'jenis'        => 'pengeluaran',
                'keterangan'   => $row['keterangan'],
                'kuantitas'    => $qty ?: null,
                'satuan'       => $row['satuan'] ?? null,
                'harga_satuan' => $harga ?: null,
                'total'        => $total,
                'urutan'       => $i,
            ]);
        }

        // Budgets: sumber dana
        foreach ($request->input('budgets_sumber_dana', []) as $i => $row) {
            if (empty($row['keterangan'])) continue;
            ProposalBudget::create([
                'proposal_id' => $proposal->id,
                'jenis'       => 'sumber_dana',
                'keterangan'  => $row['keterangan'],
                'total'       => (float) ($row['total'] ?? 0),
                'urutan'      => $i,
            ]);
        }

        return $totalPengeluaran;
    }

    /**
     * Simpan satu berkas PDF lampiran (item #7). Karena lampiran kini tunggal,
     * upload baru menggantikan lampiran lama. Tanpa upload baru, lampiran lama
     * dipertahankan (kecuali dihapus eksplisit lewat hapus_lampiran).
     */
    private function storeLampiranPdf(Proposal $proposal, Request $request): void
    {
        if (!$request->hasFile('lampiran_pdf')) return;

        // Ganti: buang lampiran PDF lama lebih dulu.
        foreach ($proposal->lampiranAttachments()->get() as $old) {
            Storage::disk('public')->delete($old->file_path);
            $old->delete();
        }

        $file = $request->file('lampiran_pdf');
        $path = $file->store('proposal-attachments/' . $proposal->id, 'public');
        ProposalAttachment::create([
            'proposal_id' => $proposal->id,
            'jenis'       => 'lampiran',
            'caption'     => 'Lampiran',
            'file_path'   => $path,
            'file_type'   => $file->getMimeType() ?: 'application/pdf',
            'urutan'      => 900,
        ]);
    }

    /**
     * Tambahkan logo UKM kolaborator (item #4) - append, tidak menghapus yang lama.
     */
    private function storeCollabLogos(Proposal $proposal, Request $request): void
    {
        if (!$request->hasFile('logo_kolaborasi')) return;

        $base = (int) $proposal->collabLogos()->max('urutan');
        foreach ($request->file('logo_kolaborasi') as $i => $file) {
            if (!$file || !$file->isValid()) continue;
            $path = $file->store('proposal-logos/collab', 'public');
            ProposalAttachment::create([
                'proposal_id' => $proposal->id,
                'jenis'       => 'collab_logo',
                'file_path'   => $path,
                'file_type'   => $file->getMimeType(),
                'urutan'      => $base + $i + 1,
            ]);
        }
    }
}
