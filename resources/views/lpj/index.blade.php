@extends('layouts.app')
@section('title', 'LPJ Saya')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 style="font-family:'Poppins','Segoe UI',sans-serif; font-size:24px; font-weight:600; color:var(--ink-900); margin-bottom:4px;">
            LPJ Saya
        </h1>
        <p style="font-size:14px; color:var(--ink-500);">Semua Laporan Pertanggungjawaban yang pernah kamu buat.</p>
    </div>
    <a href="{{ route('lpj.create') }}" class="btn-primary">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Buat LPJ Baru
    </a>
</div>

<div class="card">
    @if($lpjs->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:2px solid var(--ink-300);">
                        <th style="text-align:left; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Nama Kegiatan</th>
                        <th style="text-align:left; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Penyelenggara</th>
                        <th style="text-align:left; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Tanggal</th>
                        <th style="text-align:center; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Status</th>
                        <th style="text-align:left; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Dibuat</th>
                        <th style="text-align:right; padding:10px 12px; font-size:13px; font-weight:600; color:var(--ink-500);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lpjs as $lpj)
                    <tr style="border-bottom:1px solid var(--surface-muted); transition:background 0.15s;"
                        onmouseover="this.style.background='var(--surface-alt)'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:12px; font-size:14px; font-weight:600; color:var(--ink-900); max-width:220px;">
                            <a href="{{ route('lpj.show', $lpj) }}"
                               style="color:var(--ink-900); text-decoration:none;"
                               onmouseover="this.style.color='var(--telkom-red)'"
                               onmouseout="this.style.color='var(--ink-900)'">
                                {{ $lpj->nama_kegiatan }}
                                @if($lpj->akronim)
                                    <span style="font-size:11px; color:var(--ink-500); font-weight:400;">({{ $lpj->akronim }})</span>
                                @endif
                            </a>
                        </td>
                        <td style="padding:12px; font-size:13px; color:var(--ink-500); max-width:180px;">
                            {{ $lpj->penyelenggara ?: '-' }}
                        </td>
                        <td style="padding:12px; font-size:13px; color:var(--ink-500); white-space:nowrap;">
                            {{ $lpj->tanggal_pelaksanaan }}
                        </td>
                        <td style="padding:12px; text-align:center;">
                            @if($lpj->status === 'generated')
                                <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:var(--ink-700);">
                                    <span style="width:7px; height:7px; border-radius:50%; background:var(--success); flex-shrink:0;"></span> Sudah Dibuat
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:var(--ink-500);">
                                    <span style="width:7px; height:7px; border-radius:50%; background:var(--ink-300); flex-shrink:0;"></span> Draf
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px; font-size:13px; color:var(--ink-500); white-space:nowrap;">
                            {{ $lpj->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:12px; text-align:right; white-space:nowrap;">
                            <a href="{{ route('lpj.show', $lpj) }}"
                               style="color:var(--telkom-red); font-size:13px; font-weight:600; text-decoration:none; margin-right:12px;">
                                Lihat
                            </a>
                            <a href="{{ route('lpj.edit', $lpj) }}"
                               style="color:var(--ink-700); font-size:13px; font-weight:600; text-decoration:none; margin-right:12px;">
                                Edit
                            </a>
                            <a href="{{ route('lpj.generate', $lpj) }}"
                               style="color:var(--ink-700); font-size:13px; font-weight:600; text-decoration:none; margin-right:12px;">
                                <i data-lucide="download" class="w-3.5 h-3.5 inline"></i> PDF
                            </a>
                            <form action="{{ route('lpj.destroy', $lpj) }}" method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus LPJ ini? Semua file terkait juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:none; border:none; cursor:pointer; color:var(--danger); font-size:13px; font-weight:600; padding:0;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $lpjs->links() }}
        </div>
    @else
        <div style="text-align:center; padding:64px 0;">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4"
                 style="background:var(--surface-muted);">
                <i data-lucide="file-x" class="w-7 h-7" style="color:var(--ink-300);"></i>
            </div>
            <p style="font-size:16px; font-weight:600; color:var(--ink-700); margin-bottom:6px;">
                Belum ada LPJ
            </p>
            <p style="font-size:14px; color:var(--ink-500); margin-bottom:24px;">
                Mulai buat dokumen pertamamu menggunakan tombol di atas!
            </p>
            <a href="{{ route('lpj.create') }}" class="btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i> Buat LPJ Pertama
            </a>
        </div>
    @endif
</div>
@endsection
