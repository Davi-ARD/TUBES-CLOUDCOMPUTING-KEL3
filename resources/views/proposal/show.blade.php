@extends('layouts.app')
@section('title', $proposal->nama_kegiatan)

@section('content')
{{-- Header --}}
<div class="flex items-start justify-between mb-6 flex-wrap gap-4">
    <div>
        <a href="{{ route('proposal.index') }}"
           style="font-size:13px; color:var(--ink-500); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:8px;"
           onmouseover="this.style.color='var(--telkom-red)'" onmouseout="this.style.color='var(--ink-500)'">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        <h1 style="font-family:'Poppins','Segoe UI',sans-serif; font-size:22px; font-weight:600; color:var(--ink-900); margin-bottom:6px;">
            {{ $proposal->nama_kegiatan }}
        </h1>
        <div class="flex items-center gap-3">
            @if($proposal->status === 'generated')
                <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600; background:#E8F5E9; color:#2E7D32;">
                    <i data-lucide="check-circle" style="width:13px; height:13px;"></i> Sudah Dibuat
                </span>
            @else
                <span style="display:inline-block; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600; background:var(--surface-muted); color:var(--ink-500);">
                    Draf
                </span>
            @endif
            <span style="font-size:13px; color:var(--ink-500);">Dibuat {{ $proposal->created_at->format('d M Y') }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('proposal.generate', $proposal) }}" class="btn-primary">
            <i data-lucide="download" class="w-4 h-4"></i> Download PDF
        </a>
        <a href="{{ route('proposal.edit', $proposal) }}" class="btn-secondary">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        <form action="{{ route('proposal.destroy', $proposal) }}" method="POST"
              x-data onsubmit="return confirm('Hapus proposal ini? Semua file terkait juga akan dihapus.')">
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
            <div><dt style="color:var(--ink-500);">Penyelenggara</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->penyelenggara ?: '-' }}</dd></div>
            <div><dt style="color:var(--ink-500);">Afiliasi</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->afiliasi ?: '-' }}</dd></div>
            <div><dt style="color:var(--ink-500);">Tema</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->tema_kegiatan ?: '-' }}</dd></div>
        </dl>
    </div>
    <div class="card">
        <h3 style="font-size:12px; font-weight:600; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Waktu & Tempat</h3>
        <dl class="space-y-2 text-sm">
            <div><dt style="color:var(--ink-500);">Tanggal</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->tanggal_pelaksanaan }}</dd></div>
            <div><dt style="color:var(--ink-500);">Waktu</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->waktu_mulai ? \Carbon\Carbon::parse($proposal->waktu_mulai)->format('H:i') : '-' }} – {{ $proposal->waktu_selesai ? \Carbon\Carbon::parse($proposal->waktu_selesai)->format('H:i') : '-' }} WIB</dd></div>
            <div><dt style="color:var(--ink-500);">Tempat</dt><dd style="font-weight:600; color:var(--ink-900);">{{ $proposal->tempat_kegiatan ?: '-' }}</dd></div>
        </dl>
    </div>
    <div class="card">
        <h3 style="font-size:12px; font-weight:600; color:var(--ink-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Anggaran</h3>
        <dl class="space-y-2 text-sm">
            <div><dt style="color:var(--ink-500);">Total Pengeluaran</dt><dd style="font-weight:600; color:var(--ink-900); font-size:16px;">Rp{{ number_format($proposal->total_anggaran, 0, ',', '.') }}</dd></div>
            <div><dt style="color:var(--ink-500);">Pengajuan Dana</dt><dd style="font-weight:600; color:var(--ink-900);">Rp{{ number_format($proposal->pengajuan_dana, 0, ',', '.') }}</dd></div>
        </dl>
    </div>
</div>

{{-- Rundown --}}
@if($proposal->rundowns->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">Susunan Acara (Rundown)</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="border-bottom:2px solid var(--ink-300);">
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Mulai</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Selesai</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px;">Durasi</th>
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->rundowns as $row)
                <tr style="border-bottom:1px solid var(--surface-muted);">
                    <td style="padding:8px 10px; text-align:center;">{{ \Carbon\Carbon::parse($row->waktu_mulai)->format('H:i') }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ \Carbon\Carbon::parse($row->waktu_selesai)->format('H:i') }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $row->durasi_menit }} mnt</td>
                    <td style="padding:8px 10px;">{{ $row->aktivitas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Kepanitiaan --}}
@if($proposal->committees->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Struktur Kepanitiaan
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $proposal->committees->count() }} orang)</span>
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
                @foreach($proposal->committees as $m)
                <tr style="border-bottom:1px solid var(--surface-muted);">
                    <td style="padding:8px 10px;">{{ $m->jabatan }}</td>
                    <td style="padding:8px 10px; font-weight:600;">{{ $m->nama }}</td>
                    <td style="padding:8px 10px;">{{ $m->jurusan }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $m->tahun_angkatan }}</td>
                    <td style="padding:8px 10px;">{{ $m->nim }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Anggaran --}}
@php
    $pemasukan   = $proposal->budgets->where('jenis', 'pemasukan');
    $pengeluaran = $proposal->budgets->where('jenis', 'pengeluaran');
    $sumberDana  = $proposal->budgets->where('jenis', 'sumber_dana');
@endphp
@if($pengeluaran->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">Rencana Anggaran Biaya</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="border-bottom:2px solid var(--ink-300);">
                    <th style="text-align:left; padding:8px 10px; color:var(--ink-500); font-size:12px;">Keterangan</th>
                    <th style="text-align:center; padding:8px 10px; color:var(--ink-500); font-size:12px; width:60px;">Qty</th>
                    <th style="text-align:right; padding:8px 10px; color:var(--ink-500); font-size:12px;">Harga Satuan</th>
                    <th style="text-align:right; padding:8px 10px; color:var(--ink-500); font-size:12px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $b)
                <tr style="border-bottom:1px solid var(--surface-muted);">
                    <td style="padding:8px 10px;">{{ $b->keterangan }}</td>
                    <td style="padding:8px 10px; text-align:center;">{{ $b->kuantitas }}</td>
                    <td style="padding:8px 10px; text-align:right;">{{ $b->harga_satuan ? 'Rp'.number_format($b->harga_satuan,0,',','.') : '-' }}</td>
                    <td style="padding:8px 10px; text-align:right; font-weight:600;">Rp{{ number_format($b->total,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr style="background:var(--surface-alt); font-weight:700;">
                    <td colspan="3" style="padding:10px; text-align:right;">TOTAL</td>
                    <td style="padding:10px; text-align:right; color:var(--telkom-red);">Rp{{ number_format($pengeluaran->sum('total'),0,',','.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Risks --}}
@if($proposal->risks->count() > 0)
<div class="card mb-6">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Analisis Risiko
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $proposal->risks->count() }} item)</span>
    </h3>
    <p style="font-size:13px; color:var(--ink-500);">Data analisis risiko tersimpan dan akan muncul di PDF.</p>
</div>
@endif

{{-- Lampiran preview --}}
@if($proposal->attachments->where('jenis','lampiran')->count() > 0)
<div class="card">
    <h3 style="font-size:15px; font-weight:700; color:var(--ink-900); margin-bottom:14px;">
        Lampiran
        <span style="font-size:13px; font-weight:400; color:var(--ink-500);">({{ $proposal->attachments->where('jenis','lampiran')->count() }} file)</span>
    </h3>
    <div class="flex flex-wrap gap-3">
        @foreach($proposal->attachments->where('jenis','lampiran') as $att)
        <div class="p-3 rounded-lg border text-center" style="border-color:var(--ink-300); min-width:120px;">
            @if($att->file_type && str_starts_with($att->file_type, 'image/'))
                <img src="{{ Storage::url($att->file_path) }}" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin:0 auto 6px;">
            @else
                <div style="width:80px; height:80px; background:var(--surface-muted); border-radius:8px; margin:0 auto 6px; display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="file-text" class="w-8 h-8" style="color:var(--ink-300);"></i>
                </div>
            @endif
            <p style="font-size:11px; color:var(--ink-500); word-break:break-all;">{{ $att->caption ?: basename($att->file_path) }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
