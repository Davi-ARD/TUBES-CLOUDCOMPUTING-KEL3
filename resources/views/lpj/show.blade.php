@extends('layouts.app')
@section('title', $lpj->nama_kegiatan)

@section('content')
{{-- Header --}}
<div class="flex items-start justify-between mb-6 flex-wrap gap-4">
    <div>
        <a href="{{ route('lpj.index') }}"
           style="font-size:13px; color:var(--ink-500); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:8px;"
           onmouseover="this.style.color='var(--telkom-red)'" onmouseout="this.style.color='var(--ink-500)'">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        <h1 style="font-family:'Poppins','Segoe UI',sans-serif; font-size:22px; font-weight:600; color:var(--ink-900); margin-bottom:6px;">
            {{ $lpj->nama_kegiatan }}
            @if($lpj->akronim)
                <span style="font-size:15px; color:var(--ink-500); font-weight:400;">({{ $lpj->akronim }})</span>
            @endif
        </h1>
        <div class="flex items-center gap-3">
            @if($lpj->status === 'generated')
                <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600; background:#E8F5E9; color:#2E7D32;">
                    <i data-lucide="check-circle" style="width:13px; height:13px;"></i> Sudah Dibuat
                </span>
            @else
                <span style="display:inline-block; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600; background:var(--surface-muted); color:var(--ink-500);">
                    Draf
                </span>
            @endif
            <span style="font-size:13px; color:var(--ink-500);">Dibuat {{ $lpj->created_at->format('d M Y') }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('lpj.generate', $lpj) }}" class="btn-primary">
            <i data-lucide="download" class="w-4 h-4"></i> Download PDF
        </a>
        <a href="{{ route('lpj.edit', $lpj) }}" class="btn-secondary">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        <form action="{{ route('lpj.destroy', $lpj) }}" method="POST"
              x-data onsubmit="return confirm('Hapus LPJ ini? Semua file terkait juga akan dihapus.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary" style="color:var(--danger); border-color:var(--danger);">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
        </form>
    </div>
</div>

{{-- Info utama --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card">
        <h3 style="font-size:12px; font-weight:600; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Identitas Kegiatan</h3>
        <dl class="space-y-2 text-sm">
            <div><dt style="color:var(--ink-500);">Penyelenggara</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $lpj->penyelenggara ?: '-' }}</dd></div>
            <div><dt style="color:var(--ink-500);">Afiliasi</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $lpj->afiliasi ?: '-' }}</dd></div>
            <div><dt style="color:var(--ink-500);">Tema</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $lpj->tema_kegiatan ?: '-' }}</dd></div>
        </dl>
    </div>
    <div class="card">
        <h3 style="font-size:12px; font-weight:600; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Waktu & Tempat</h3>
        <dl class="space-y-2 text-sm">
            <div><dt style="color:var(--ink-500);">Tanggal</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $lpj->tanggal_pelaksanaan }}</dd></div>
            <div><dt style="color:var(--ink-500);">Waktu</dt><dd style="font-weight:600; color:var(--ink-900);">
                {{ $lpj->waktu_mulai ? \Carbon\Carbon::parse($lpj->waktu_mulai)->format('H:i') : '-' }}
                –
                {{ $lpj->waktu_selesai ? \Carbon\Carbon::parse($lpj->waktu_selesai)->format('H:i') : '-' }} WIB
            </dd></div>
            <div><dt style="color:var(--ink-500);">Tempat</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $lpj->tempat_kegiatan ?: '-' }}</dd></div>
        </dl>
    </div>
    <div class="card">
        <h3 style="font-size:12px; font-weight:600; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Anggaran Realisasi</h3>
        <dl class="space-y-2 text-sm">
            @php
                $totalMasuk  = $lpj->budgets->where('jenis','dana_masuk')->sum('jumlah_total');
                $totalKeluar = $lpj->budgets->where('jenis','dana_keluar')->where('is_kategori', false)->sum('jumlah_total');
            @endphp
            <div><dt style="color:var(--ink-500);">Dana Masuk</dt><dd style="font-weight:600; color:var(--ink-900);">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</dd></div>
            <div><dt style="color:var(--ink-500);">Dana Keluar</dt><dd style="font-weight:600; color:var(--ink-900);">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</dd></div>
        </dl>
    </div>
</div>

{{-- Rundown --}}
@if($lpj->rundowns->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">Susunan Acara (Rundown)</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="border-bottom:2px solid var(--ink-300);">
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Mulai</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Selesai</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Durasi</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Detail Kegiatan</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">PIC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lpj->rundowns as $row)
                <tr style="border-bottom:1px solid var(--surface-muted);">
                    <td style="padding:8px 10px; text-align:center;">{{ $row->waktu_mulai }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $row->waktu_selesai }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $row->durasi }}</td>
                    <td style="padding:8px 10px;">{{ $row->detail_kegiatan }}</td>
                    <td style="padding:8px 10px;">{{ $row->pic }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Monitoring --}}
@if($lpj->monitoringGroups->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        K. Monitoring dan Evaluasi
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $lpj->monitoringGroups->count() }} tanggal/fase, {{ $lpj->monitoringGroups->sum(fn($g) => $g->items->count()) }} detail kegiatan)</span>
    </h3>
    <p style="font-size:13px; color:var(--ink-500);">Data monitoring tersimpan dan akan muncul di PDF.</p>
</div>
@endif

{{-- Kepanitiaan --}}
@if($lpj->committees->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Struktur Kepanitiaan
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $lpj->committees->count() }} orang)</span>
    </h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="border-bottom:2px solid var(--ink-300);">
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Jabatan</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Nama</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Jurusan</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Angkatan</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">NIM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lpj->committees as $m)
                <tr style="border-bottom:1px solid var(--surface-muted);">
                    <td style="padding:8px 10px;">{{ $m->jabatan }}</td>
                    <td style="padding:8px 10px; font-weight:600;">{{ $m->nama }}</td>
                    <td style="padding:8px 10px;">{{ $m->jurusan }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $m->angkatan }}</td>
                    <td style="padding:8px 10px;">{{ $m->nim }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Risks --}}
@if($lpj->risks->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Analisis Risiko
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $lpj->risks->count() }} item)</span>
    </h3>
    <p style="font-size:13px; color:var(--ink-500);">Data analisis risiko tersimpan dan akan muncul di PDF.</p>
</div>
@endif

{{-- Lampiran preview --}}
@php $lampiranAll = $lpj->attachments->whereIn('jenis', ['lampiran','nota','bukti_transfer','dokumentasi','poster','lainnya']); @endphp
@if($lampiranAll->count() > 0)
<div class="card">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Lampiran
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $lampiranAll->count() }} file)</span>
    </h3>
    <div class="flex flex-wrap gap-3">
        @foreach($lampiranAll as $att)
        <div class="p-3 rounded-lg border text-center" style="border-color:var(--ink-300); min-width:120px;">
            @if($att->file_type && str_starts_with($att->file_type, 'image/'))
                <img src="{{ Storage::url($att->file_path) }}" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin:0 auto 6px;">
            @else
                <div style="width:80px; height:80px; background:var(--surface-muted); border-radius:8px; margin:0 auto 6px; display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="file-text" class="w-8 h-8" style="color:var(--ink-300);"></i>
                </div>
            @endif
            <p style="font-size:11px; color:var(--ink-500); word-break:break-all;">{{ $att->caption ?: basename($att->file_path) }}</p>
            <p style="font-size:10px; color:var(--ink-300); text-transform:uppercase;">{{ $att->jenis }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
