@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="overflow-x-hidden">
    {{-- Status Server (penanda instance untuk Load Balancer) --}}
    @php
        $serverNum = config('app.server_id') ?: '?';
    @endphp
    <div class="mb-6" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px;
                background:var(--surface); border:1px solid var(--surface-muted);
                border-radius:var(--radius-card); box-shadow:var(--shadow-card); padding:14px 18px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px;
                         border-radius:10px; background:var(--surface-alt); border:1px solid var(--surface-muted);">
                <i data-lucide="server" class="w-[18px] h-[18px]" style="color:var(--ink-500);"></i>
            </span>
            <div>
                <p style="font-size:11px; font-weight:600; letter-spacing:0.04em; text-transform:uppercase; color:var(--ink-400); margin-bottom:2px;">
                    Dilayani oleh
                </p>
                <p style="font-size:16px; font-weight:600; color:var(--ink-800); line-height:1.1;">
                    Server {{ $serverNum }}
                </p>
            </div>
        </div>
        <div style="font-size:12.5px; color:var(--ink-400);">
            <span style="color:var(--ink-500); font-weight:600;">Host:</span> {{ gethostname() }}
        </div>
    </div>

    {{-- Greeting --}}
    <div class="mb-8">
        <h1 style="font-family: 'Poppins', 'Segoe UI', sans-serif; font-size: 28px; font-weight: 500; color: var(--ink-800); margin-bottom: 8px;">
            Selamat datang, {{ auth()->user()->name }}!
        </h1>
        <p style="font-size: 15px; color: var(--ink-500);">
            Apa yang ingin kamu buat hari ini?
        </p>
    </div>

    {{-- Tim Pengembang --}}
    @php
        $teamMembers = [
            ['name' => 'Muhammad Davi Ardian',      'nim' => '102022330384', 'photo' => 'DAPI.jpeg'],
            ['name' => 'Marshall Rasendria Mahendra','nim' => '102022300097', 'photo' => 'MARSHALL.jpeg'],
            ['name' => 'Bahaa Azzikra Azhar Arma',  'nim' => '102022300404', 'photo' => 'BAHAA.jpeg'],
            ['name' => 'Avanindra Wira Wicaksana',  'nim' => '102022300348', 'photo' => 'ANDRAA.jpeg'],
        ];
    @endphp

    <div class="card mb-10">
        <div class="flex items-center gap-2 mb-1">
            <i data-lucide="users" class="w-[18px] h-[18px]" style="color: var(--telkom-red);"></i>
            <h2 style="font-size: 16px; font-weight: 600; color: var(--ink-800);">
                Tim Pengembang
            </h2>
        </div>
        <p style="font-size: 13px; color: var(--ink-500); margin-bottom: 20px;">
            Kelompok 3 &mdash; Tugas Besar Cloud Computing
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($teamMembers as $member)
                <div style="text-align: center; transition: var(--transition);"
                     onmouseover="this.style.transform='translateY(-3px)';"
                     onmouseout="this.style.transform='translateY(0)';">
                    <img src="{{ asset('img/' . $member['photo']) }}"
                         alt="{{ $member['name'] }}"
                         class="mx-auto"
                         style="width: 100%; max-width: 140px; aspect-ratio: 1 / 1; object-fit: cover; border-radius: var(--radius-card); border: 3px solid var(--surface); box-shadow: var(--shadow-card); background: var(--surface-muted);">
                    <h3 style="font-size: 14px; font-weight: 600; color: var(--ink-800); margin-top: 14px; line-height: 1.4;">
                        {{ $member['name'] }}
                    </h3>
                    <p style="font-size: 12px; font-weight: 500; color: var(--telkom-red); margin-top: 2px;">
                        {{ $member['nim'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 2 Card Besar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        {{-- Card Proposal --}}
        <div class="card group" style="transition: var(--transition); cursor: default;"
             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
            <div class="flex items-start gap-4">
                <img src="{{ asset('img/icon-proposak.png') }}" alt="Proposal" class="shrink-0"
                     style="width: 44px; height: 44px; object-fit: contain;">
                <div class="flex-1">
                    <h3 style="font-size: 17px; font-weight: 600; color: var(--ink-800); margin-bottom: 8px;">
                        Proposal Kegiatan
                    </h3>
                    <p style="font-size: 14px; color: var(--ink-500); line-height: 1.6; margin-bottom: 20px;">
                        Buat proposal kegiatan UKM untuk diajukan ke Direktorat Kemahasiswaan. Lengkap dengan rundown, anggaran, dan lembar pengesahan.
                    </p>
                    <a href="{{ route('proposal.create') }}" class="btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Buat Proposal
                    </a>
                </div>
            </div>
        </div>

        {{-- Card LPJ --}}
        <div class="card group" style="transition: var(--transition); cursor: default;"
             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
            <div class="flex items-start gap-4">
                <img src="{{ asset('img/icon-laporan.png') }}" alt="LPJ" class="shrink-0"
                     style="width: 44px; height: 44px; object-fit: contain;">
                <div class="flex-1">
                    <h3 style="font-size: 17px; font-weight: 600; color: var(--ink-800); margin-bottom: 8px;">
                        Laporan Pertanggungjawaban (LPJ)
                    </h3>
                    <p style="font-size: 14px; color: var(--ink-500); line-height: 1.6; margin-bottom: 20px;">
                        Buat LPJ pasca kegiatan sebagai bentuk akuntabilitas pelaksanaan program. Lengkap dengan realisasi anggaran dan dokumentasi.
                    </p>
                    <a href="{{ route('lpj.create') }}" class="btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Buat LPJ
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen Terakhir --}}
    <div class="card">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h2 style="font-size: 16px; font-weight: 600; color: var(--ink-800);">
                Dokumen Terakhir
            </h2>
            @if($recentDocuments->count() > 0)
                <div class="flex items-center gap-4">
                    <a href="{{ route('proposal.index') }}"
                       style="font-size: 13px; color: var(--telkom-red); text-decoration: none; font-weight: 600;">
                        Semua Proposal
                    </a>
                    <a href="{{ route('lpj.index') }}"
                       style="font-size: 13px; color: var(--telkom-red); text-decoration: none; font-weight: 600;">
                        Semua LPJ
                    </a>
                </div>
            @endif
        </div>

        @if($recentDocuments->count() > 0)
            <div class="overflow-x-auto w-full">
                <table style="width: 100%; border-collapse: collapse; min-width: 560px;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--ink-300);">
                            <th style="text-align:left; padding: 8px 10px; font-size: 12px; font-weight: 600; color: var(--ink-500);">Nama Kegiatan</th>
                            <th style="text-align:left; padding: 8px 10px; font-size: 12px; font-weight: 600; color: var(--ink-500);">Tipe</th>
                            <th style="text-align:left; padding: 8px 10px; font-size: 12px; font-weight: 600; color: var(--ink-500);">Tanggal Dibuat</th>
                            <th style="text-align:center; padding: 8px 10px; font-size: 12px; font-weight: 600; color: var(--ink-500);">Status</th>
                            <th style="text-align:right; padding: 8px 10px; font-size: 12px; font-weight: 600; color: var(--ink-500);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDocuments as $doc)
                        <tr style="border-bottom: 1px solid var(--surface-muted);">
                            <td style="padding: 10px; font-size: 14px; font-weight: 500; color: var(--ink-800);">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="{{ $doc['icon'] }}" class="w-4 h-4 shrink-0" style="color: var(--ink-400);"></i>
                                    <a href="{{ $doc['show_url'] }}"
                                       style="color: var(--ink-900); text-decoration: none;"
                                       onmouseover="this.style.color='var(--telkom-red)'"
                                       onmouseout="this.style.color='var(--ink-900)'">
                                        {{ $doc['nama'] }}
                                    </a>
                                </span>
                            </td>
                            <td style="padding: 10px;">
                                @if($doc['type'] === 'Proposal')
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; background:var(--surface-alt); border:1px solid var(--ink-300); color:var(--ink-700);">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Proposal
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:600; background:var(--surface-alt); border:1px solid var(--ink-300); color:var(--ink-700);">
                                        <i data-lucide="clipboard-list" class="w-3.5 h-3.5"></i> LPJ
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px; font-size: 13px; color: var(--ink-500);">
                                {{ $doc['tanggal'] }}
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                @if($doc['status'] === 'generated')
                                    <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:var(--ink-700);">
                                        <span style="width:7px; height:7px; border-radius:50%; background:var(--success); flex-shrink:0;"></span> Sudah Dibuat
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; color:var(--ink-500);">
                                        <span style="width:7px; height:7px; border-radius:50%; background:var(--ink-300); flex-shrink:0;"></span> Draf
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px; text-align: right; white-space: nowrap;">
                                <a href="{{ $doc['download_url'] }}"
                                   style="font-size: 13px; font-weight: 600; color: var(--telkom-red); text-decoration: none; margin-right: 14px;">
                                    <i data-lucide="download" class="w-3.5 h-3.5 inline"></i> Unduh PDF
                                </a>
                                <a href="{{ $doc['show_url'] }}"
                                   style="font-size: 13px; font-weight: 600; color: var(--ink-500); text-decoration: none;">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 48px 0;">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background: var(--surface-muted);">
                    <i data-lucide="folder-open" class="w-8 h-8" style="color: var(--ink-300);"></i>
                </div>
                <p style="font-size: 15px; font-weight: 600; color: var(--ink-700); margin-bottom: 4px;">
                    Belum ada dokumen
                </p>
                <p style="font-size: 13px; color: var(--ink-500); margin-bottom: 20px;">
                    Mulai buat proposal atau LPJ baru.
                </p>
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <a href="{{ route('proposal.create') }}" class="btn-primary">
                        <i data-lucide="file-plus-2" class="w-4 h-4"></i>
                        Buat Proposal
                    </a>
                    <a href="{{ route('lpj.create') }}" class="btn-secondary">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        Buat LPJ
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
