{{-- A. Latar Belakang --}}
<p class="section-title">A. Latar Belakang</p>
<div class="rich">{!! \App\Support\RichText::render($proposal->latar_belakang) !!}</div>

{{-- B. Nama Kegiatan --}}
<p class="section-title">B. Nama Kegiatan</p>
<p>{{ $proposal->nama_kegiatan }}</p>

{{-- C. Tema Kegiatan --}}
<p class="section-title">C. Tema Kegiatan</p>
<p>{{ $proposal->tema_kegiatan ?: '-' }}</p>

{{-- D. Tujuan Kegiatan --}}
<p class="section-title">D. Tujuan Kegiatan</p>
<p>{{ $proposal->nama_kegiatan }} memiliki tujuan sebagai berikut:</p>
@if(!empty($proposal->tujuan_kegiatan))
<ol>
    @foreach($proposal->tujuan_kegiatan as $tujuan)
        @if(trim($tujuan))
            <li>{{ $tujuan }}</li>
        @endif
    @endforeach
</ol>
@endif

{{-- E. Sasaran --}}
<p class="section-title">E. Sasaran Kegiatan</p>
<div class="rich">{!! \App\Support\RichText::render($proposal->sasaran_kegiatan) !!}</div>

{{-- F. Waktu dan Tempat --}}
<p class="section-title">F. Waktu dan Tempat</p>
<table class="no-border" style="width:auto; margin-bottom:8pt;">
    <tr>
        <td style="width:130pt; padding:1pt 0;">Hari, Tanggal</td>
        <td style="padding:1pt 0;">: {{ $proposal->tanggal_pelaksanaan }}</td>
    </tr>
    <tr>
        <td style="padding:1pt 0;">Waktu</td>
        <td style="padding:1pt 0;">:
            {{ $proposal->waktu_mulai ? \Carbon\Carbon::parse($proposal->waktu_mulai)->format('H.i') : '-' }}-{{ $proposal->waktu_selesai ? \Carbon\Carbon::parse($proposal->waktu_selesai)->format('H.i') : '-' }} WIB
        </td>
    </tr>
    <tr>
        <td style="padding:1pt 0;">Tempat</td>
        <td style="padding:1pt 0;">: {{ $proposal->tempat_kegiatan }}</td>
    </tr>
</table>

{{-- G. Bentuk Kegiatan --}}
<p class="section-title">G. Bentuk Kegiatan</p>
<div class="rich">{!! \App\Support\RichText::render($proposal->bentuk_kegiatan) !!}</div>

{{-- H. Materi --}}
<p class="section-title">H. Materi Kegiatan</p>
<p>Materi yang disampaikan pada kegiatan kali ini adalah:</p>
@if(!empty($proposal->materi_kegiatan))
<ol>
    @foreach($proposal->materi_kegiatan as $materi)
        @if(!empty($materi['judul']))
        <li>
            <span class="bold">{{ $materi['judul'] }}</span>
            @if(!empty($materi['deskripsi']))
                <br>{!! nl2br(e($materi['deskripsi'])) !!}
            @endif
        </li>
        @endif
    @endforeach
</ol>
@endif

{{-- I. Narasumber --}}
<p class="section-title">I. Narasumber Kegiatan</p>
<p>{!! nl2br(e($proposal->narasumber_kegiatan)) !!}</p>

{{-- J. Rundown --}}
<p class="section-title">J. Susunan Acara (Rundown)</p>
@if($proposal->rundowns->count() > 0)
<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color:#E03A3E; color:#fff; text-align:center; font-weight:bold;">{{ $proposal->nama_kegiatan }}</th>
        </tr>
        <tr>
            <th colspan="4" style="background-color:#E03A3E; color:#fff; text-align:center; font-weight:bold;">{{ $proposal->tanggal_mulai->locale('id')->translatedFormat('l') }}, {{ $proposal->tanggal_pelaksanaan }}</th>
        </tr>
        <tr>
            <th style="text-align:center;">Start</th>
            <th style="text-align:center;">End</th>
            <th style="text-align:center;">Duration</th>
            <th>Activities</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposal->rundowns as $row)
        <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($row->waktu_mulai)->format('H:i') }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($row->waktu_selesai)->format('H:i') }}</td>
            <td class="text-center">{{ $row->durasi_menit }}</td>
            <td>{{ $row->aktivitas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- K. Analisis Resiko --}}
<p class="section-title">K. Analisis Resiko</p>
<table style="font-size: 11pt; margin-bottom: 8pt; border-collapse: collapse;">
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Nama Kegiatan</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $proposal->nama_kegiatan }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Ketua Kegiatan</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $proposal->ketua_pelaksana_nama }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Dosen Pembina</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $proposal->pembina_nama }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Lokasi</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $proposal->tempat_kegiatan }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 0 0; vertical-align:top; white-space:nowrap;">Tanggal Dibuat</td>
        <td style="border:none; padding:0 6pt 0 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0; vertical-align:top;">{{ now()->locale('id')->translatedFormat('j F Y') }}</td>
    </tr>
</table>

@if($proposal->risks->count() > 0)
<table style="width:100%; border-collapse: collapse; table-layout: fixed; font-size: 9pt; word-wrap: break-word;">
    <colgroup>
        <col style="width:4%">
        <col style="width:17%">
        <col style="width:7%">
        <col style="width:7%">
        <col style="width:13%">
        <col style="width:7%">
        <col style="width:29%">
        <col style="width:16%">
    </colgroup>
    <thead>
        <tr style="background-color:#f0f0f0;">
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">No</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Uraian Kegiatan</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Identifikasi Bahaya</th>
            <th colspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Penilaian Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Tingkat Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Pengendalian Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Penanggung Jawab</th>
        </tr>
        <tr style="background-color:#f0f0f0;">
            <th style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Peluang/<br>kemungkinan</th>
            <th style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Akibat/<br>keparahan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposal->risks as $i => $risk)
        <tr>
            <td style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">{{ $i + 1 }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; font-size:9pt; word-wrap:break-word;">{{ $risk->uraian_kegiatan }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; font-size:9pt; word-wrap:break-word;">{{ $risk->identifikasi_bahaya }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">{{ $risk->peluang }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">{{ $risk->akibat }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">{{ $risk->tingkat_risiko }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; font-size:9pt; word-wrap:break-word;">{{ $risk->pengendalian_risiko }}</td>
            <td style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt; word-wrap:break-word;">{{ $risk->penanggung_jawab }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- L. Monitoring & Evaluasi --}}
<p class="section-title">L. Monitoring dan Evaluasi</p>
<div class="rich">{!! \App\Support\RichText::render($proposal->monitoring_evaluasi) !!}</div>

{{-- M. Kepanitiaan --}}
<p class="section-title">M. Struktur Kepanitiaan</p>
@if($proposal->committees->count() > 0)
<table>
    <thead>
        <tr>
            <th>Jabatan</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th style="width:50pt;">Tahun</th>
            <th>Fakultas</th>
            <th style="width:80pt;">NIM</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposal->committees as $member)
        <tr>
            <td>{{ $member->jabatan }}</td>
            <td>{{ $member->nama }}</td>
            <td>{{ $member->jurusan }}</td>
            <td class="text-center">{{ $member->tahun_angkatan }}</td>
            <td>{{ $member->fakultas }}</td>
            <td>{{ $member->nim }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- N. Anggaran --}}
<p class="section-title">N. Rencana Anggaran Biaya</p>

@php
    // ->values() me-reset key koleksi ke 0,1,2,... agar penomoran ($i + 1) restart dari 1 tiap tabel
    $pemasukan   = $proposal->budgets->where('jenis', 'pemasukan')->values();
    $pengeluaran = $proposal->budgets->where('jenis', 'pengeluaran')->values();
    $sumberDana  = $proposal->budgets->where('jenis', 'sumber_dana')->values();
@endphp

<p class="bold">1. Pemasukan</p>
<table>
    <thead><tr><th style="width:5%;">No.</th><th>Keterangan</th><th style="width:130pt;">Total (Rp)</th></tr></thead>
    <tbody>
        @foreach($pemasukan as $i => $b)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $b->keterangan }}</td>
            <td class="currency">{{ number_format($b->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-right bold">Total Pemasukan</td>
            <td class="currency bold">{{ number_format($pemasukan->sum('total'), 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<p class="bold">2. Pengeluaran</p>
<table style="font-size:9.5pt; table-layout:fixed; word-wrap:break-word;">
    <colgroup>
        <col style="width:6%">    {{-- No --}}
        <col style="width:38%">   {{-- Keterangan --}}
        <col style="width:13%">   {{-- Kuantitas --}}
        <col style="width:12%">   {{-- Satuan --}}
        <col style="width:16%">   {{-- Harga --}}
        <col style="width:15%">   {{-- Total --}}
    </colgroup>
    <thead>
        <tr>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">No.</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">Keterangan</th>
            <th colspan="3" style="text-align:center;">Jumlah</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle;">Total</th>
        </tr>
        <tr>
            <th style="text-align:center;">Kuantitas</th>
            <th style="text-align:center;">Satuan</th>
            <th style="text-align:center;">Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengeluaran as $i => $b)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $b->keterangan }}</td>
            <td class="text-center">{{ $b->kuantitas }}</td>
            <td>{{ $b->satuan }}</td>
            <td class="currency">{{ $b->harga_satuan ? number_format($b->harga_satuan, 0, ',', '.') : '-' }}</td>
            <td class="currency">{{ number_format($b->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-right bold">TOTAL PENGELUARAN</td>
            <td class="currency bold">{{ number_format($pengeluaran->sum('total'), 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<p class="bold">3. Sumber Dana</p>
<table>
    <thead><tr><th style="width:5%;">No.</th><th>Keterangan</th><th style="width:130pt;">Total (Rp)</th></tr></thead>
    <tbody>
        @foreach($sumberDana as $i => $b)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $b->keterangan }}</td>
            <td class="currency">{{ number_format($b->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-right bold">Total Sumber Dana</td>
            <td class="currency bold">{{ number_format($sumberDana->sum('total'), 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="page-break"></div>

{{-- O. Penutup --}}
<p class="section-title">O. Penutup</p>
<div class="rich">{!! \App\Support\RichText::render($proposal->penutup) !!}</div>
