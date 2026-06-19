{{-- A. Latar Belakang --}}
<p class="section-title">A. Latar Belakang</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->latar_belakang) !!}</div>

{{-- B. Nama Kegiatan --}}
<p class="section-title">B. Nama Kegiatan</p>
<p>{{ $lpj->nama_kegiatan }}</p>

{{-- C. Tema Kegiatan --}}
<p class="section-title">C. Tema Kegiatan</p>
<p>{{ $lpj->tema_kegiatan ?: '-' }}</p>

{{-- D. Tujuan Kegiatan --}}
<p class="section-title">D. Tujuan Kegiatan</p>
<p>{{ $lpj->nama_kegiatan }} memiliki tujuan sebagai berikut:</p>
@if(!empty($lpj->tujuan_kegiatan))
<ol>
    @foreach($lpj->tujuan_kegiatan as $tujuan)
        @if(trim($tujuan))
            <li>{{ $tujuan }}</li>
        @endif
    @endforeach
</ol>
@endif

{{-- E. Sasaran --}}
<p class="section-title">E. Sasaran Kegiatan</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->sasaran_kegiatan) !!}</div>

{{-- F. Waktu dan Tempat --}}
<p class="section-title">F. Waktu dan Tempat</p>
<table class="no-border" style="width:auto; margin-bottom:8pt;">
    <tr>
        <td style="width:130pt; padding:1pt 0;">Hari, Tanggal</td>
        <td style="padding:1pt 0;">: {{ $lpj->tanggal_pelaksanaan }}</td>
    </tr>
    <tr>
        <td style="padding:1pt 0;">Waktu</td>
        <td style="padding:1pt 0;">:
            {{ $lpj->waktu_mulai ? \Carbon\Carbon::parse($lpj->waktu_mulai)->format('H.i') : '-' }}–{{ $lpj->waktu_selesai ? \Carbon\Carbon::parse($lpj->waktu_selesai)->format('H.i') : '-' }} WIB
        </td>
    </tr>
    <tr>
        <td style="padding:1pt 0;">Tempat</td>
        <td style="padding:1pt 0;">: {{ $lpj->tempat_kegiatan }}</td>
    </tr>
</table>

{{-- G. Bentuk Kegiatan --}}
<p class="section-title">G. Bentuk Kegiatan</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->bentuk_kegiatan) !!}</div>

{{-- H. Deskripsi Pelaksanaan --}}
<p class="section-title">H. Deskripsi Pelaksanaan Kegiatan</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->deskripsi_pelaksanaan) !!}</div>

{{-- I. Rundown --}}
<p class="section-title">I. Susunan Acara (Rundown)</p>
@if($lpj->rundowns->count() > 0)
<table>
    <colgroup>
        <col style="width:20%">
        <col style="width:12%">
        <col style="width:48%">
        <col style="width:20%">
    </colgroup>
    <thead>
        <tr>
            <th colspan="4" style="text-align:center; background-color:#d0d0d0; font-weight:bold;">{{ $lpj->nama_kegiatan }}</th>
        </tr>
        <tr>
            <th style="text-align:center;">Pukul</th>
            <th style="text-align:center;">Durasi</th>
            <th style="text-align:center;">Detail Kegiatan</th>
            <th style="text-align:center;">PIC</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lpj->rundowns as $row)
        <tr>
            <td class="text-center">
                @php
                    $wm = $row->waktu_mulai ? str_replace(':', '.', substr($row->waktu_mulai, 0, 5)) : '-';
                    $ws = $row->waktu_selesai ? str_replace(':', '.', substr($row->waktu_selesai, 0, 5)) : '-';
                @endphp
                {{ $wm }} – {{ $ws }}
            </td>
            <td class="text-center">{{ $row->durasi }}</td>
            <td>{{ $row->detail_kegiatan }}</td>
            <td>{{ $row->pic }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- J. Analisis Risiko --}}
<p class="section-title">J. Analisis Risiko</p>
<table style="font-size: 11pt; margin-bottom: 8pt; border-collapse: collapse;">
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Nama Kegiatan</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $lpj->nama_kegiatan }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Ketua Kegiatan</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $lpj->ketua_pelaksana_nama }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Dosen Pembina</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $lpj->pembina_1_nama }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 1pt 0; vertical-align:top; white-space:nowrap;">Lokasi</td>
        <td style="border:none; padding:0 6pt 1pt 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0 0 1pt 0; vertical-align:top;">{{ $lpj->tempat_kegiatan }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:0 4pt 0 0; vertical-align:top; white-space:nowrap;">Tanggal Dibuat</td>
        <td style="border:none; padding:0 6pt 0 0; vertical-align:top;">:</td>
        <td style="border:none; padding:0; vertical-align:top;">{{ now()->locale('id')->translatedFormat('j F Y') }}</td>
    </tr>
</table>

@if($lpj->risks->count() > 0)
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
        <tr>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">No</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Uraian Kegiatan</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Identifikasi Bahaya</th>
            <th colspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Penilaian Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Tingkat Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Pengendalian Risiko</th>
            <th rowspan="2" style="border:1px solid #000; padding:3pt 4pt; text-align:center; vertical-align:middle; font-size:9pt;">Penanggung Jawab</th>
        </tr>
        <tr>
            <th style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Peluang/<br>kemungkinan</th>
            <th style="border:1px solid #000; padding:3pt 4pt; text-align:center; font-size:9pt;">Akibat/<br>keparahan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lpj->risks as $i => $risk)
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

{{-- K. Monitoring dan Evaluasi --}}
<p class="section-title">K. Monitoring dan Evaluasi</p>
@php
    \Carbon\Carbon::setLocale('id');
    $monCell = 'border:1px solid #000; padding:5px 6px; font-size:9pt; vertical-align:top; word-wrap:break-word;';
@endphp
@if($lpj->monitoringGroups->count() > 0)
<table style="width:100%; border-collapse:collapse; table-layout:fixed; font-size:9pt;">
    <colgroup>
        <col style="width:22%">
        <col style="width:28%">
        <col style="width:22%">
        <col style="width:28%">
    </colgroup>
    <thead>
        <tr>
            <th style="{{ $monCell }} text-align:center; font-weight:bold;">Tanggal</th>
            <th style="{{ $monCell }} text-align:center; font-weight:bold;">Detail Kegiatan</th>
            <th style="{{ $monCell }} text-align:center; font-weight:bold;">PIC</th>
            <th style="{{ $monCell }} text-align:center; font-weight:bold;">Keterangan Monitoring/Evaluasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lpj->monitoringGroups as $group)
            @php $itemCount = max($group->items->count(), 1); @endphp
            @forelse($group->items as $itemIndex => $item)
            <tr>
                @if($itemIndex === 0)
                <td rowspan="{{ $itemCount }}" style="{{ $monCell }}">
                    @if($group->tanggal)
                        <span style="font-weight:bold;">{{ \Carbon\Carbon::parse($group->tanggal)->translatedFormat('j F Y') }}</span>
                    @endif
                    @if($group->fase)
                        <br><span style="font-weight:normal; font-style:italic;">({{ $group->fase }})</span>
                    @endif
                </td>
                @endif
                <td style="{{ $monCell }}">{{ $item->detail_kegiatan }}</td>
                <td style="{{ $monCell }}">{{ $item->pic }}</td>
                <td style="{{ $monCell }}">{{ $item->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td style="{{ $monCell }}">
                    @if($group->tanggal)
                        <span style="font-weight:bold;">{{ \Carbon\Carbon::parse($group->tanggal)->translatedFormat('j F Y') }}</span>
                    @endif
                    @if($group->fase)
                        <br><span style="font-weight:normal; font-style:italic;">({{ $group->fase }})</span>
                    @endif
                </td>
                <td style="{{ $monCell }}"></td>
                <td style="{{ $monCell }}"></td>
                <td style="{{ $monCell }}"></td>
            </tr>
            @endforelse
        @endforeach
    </tbody>
</table>
@endif

{{-- L. Simpulan dan Rekomendasi --}}
<p class="section-title">L. Simpulan dan Rekomendasi</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->simpulan_rekomendasi) !!}</div>

{{-- M. Kepanitiaan --}}
<p class="section-title">M. Struktur Kepanitiaan</p>
@if($lpj->committees->count() > 0)
<table>
    <colgroup>
        <col style="width:22%">
        <col style="width:18%">
        <col style="width:18%">
        <col style="width:18%">
        <col style="width:14%">
        <col style="width:10%">
    </colgroup>
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>Fakultas</th>
            <th>Jabatan</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lpj->committees as $member)
        <tr>
            <td>{{ $member->nama }}</td>
            <td>{{ $member->nim }}</td>
            <td>{{ $member->jurusan }}</td>
            <td>{{ $member->fakultas }}</td>
            <td>{{ $member->jabatan }}</td>
            <td class="text-center">{{ $member->angkatan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- N. Realisasi Anggaran --}}
<p class="section-title">N. Realisasi Anggaran Biaya</p>

@php
    $danaMasuk  = $lpj->budgets->where('jenis', 'dana_masuk');
    $danaKeluar = $lpj->budgets->where('jenis', 'dana_keluar');
@endphp

<p class="bold">1. Dana Masuk</p>
<table>
    <colgroup>
        <col style="width:6%">
        <col style="width:40%">
        <col style="width:18%">
        <col style="width:18%">
        <col style="width:18%">
    </colgroup>
    <thead>
        <tr>
            <th>No</th>
            <th>Sumber Dana</th>
            <th>Target</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($danaMasuk as $i => $b)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $b->sumber_dana }}</td>
            <td class="currency">{{ $b->target ? 'Rp' . number_format($b->target, 0, ',', '.') : '-' }}</td>
            <td class="currency">Rp{{ number_format($b->jumlah_total, 0, ',', '.') }}</td>
            <td class="currency">Rp{{ number_format($b->jumlah_total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-right bold">Total Keseluruhan</td>
            <td class="currency bold">Rp{{ number_format($danaMasuk->sum('jumlah_total'), 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<p class="bold">2. Dana Keluar</p>
@php
    $divisions = $lpj->danaKeluarDivisions()->with(['categories.subitems'])->orderBy('urutan')->get();
    $grandTotalKeluar = 0;
@endphp

@if($divisions->count() > 0)
{{--
    Tabel dibuat tanpa kolom "Divisi" dan "No". Kolom tersebut pada template
    aslinya di-merge (rowspan) membentang ke seluruh baris di bawahnya, namun
    dompdf tidak merender rowspan dengan benar ketika sel yang membentang
    melewati batas halaman (sel menjadi kosong di halaman lanjutan). Sebagai
    gantinya, Divisi dan Kategori ditampilkan sebagai baris grup penuh (colspan)
    yang aman terhadap page break.
--}}
<table style="width:100%; border-collapse:collapse; font-size:9.5pt; table-layout:fixed;">
    <colgroup>
        <col style="width:40%">
        <col style="width:10%">
        <col style="width:12%">
        <col style="width:19%">
        <col style="width:19%">
    </colgroup>
    <thead>
        <tr style="background:#f0f0f0;">
            <th style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-weight:bold;">Rincian Kebutuhan</th>
            <th style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-weight:bold;">Jumlah</th>
            <th style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-weight:bold;">Satuan</th>
            <th style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-weight:bold;">Harga Satuan</th>
            <th style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-weight:bold;">Jumlah Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($divisions as $divisi)
            {{-- Baris divisi: grup penuh (colspan 5) --}}
            <tr>
                <td colspan="5"
                    style="border:1px solid #000; padding:4pt 6pt; text-align:left; font-weight:bold; background:#e8e8e8;">
                    Divisi: {{ $divisi->nama_divisi }}
                </td>
            </tr>

            @foreach ($divisi->categories as $kategori)
                {{-- Baris kategori: grup penuh (colspan 5) --}}
                <tr>
                    <td colspan="5"
                        style="border:1px solid #000; padding:4pt 5pt; text-align:center; font-style:italic; background:#f9f9f9;">
                        {{ $kategori->nama_kategori }}
                    </td>
                </tr>

                @foreach ($kategori->subitems as $subitem)
                    @php
                        $jumlahHarga = $subitem->jumlah * $subitem->harga_satuan;
                        $grandTotalKeluar += $jumlahHarga;
                        $qtyDisplay = ($subitem->jumlah == floor($subitem->jumlah))
                            ? (int) $subitem->jumlah
                            : $subitem->jumlah;
                    @endphp
                    <tr>
                        <td style="border:1px solid #000; padding:4pt 8pt; word-wrap:break-word;">
                            {{ $subitem->rincian_kebutuhan }}
                        </td>
                        <td style="border:1px solid #000; padding:4pt 5pt; text-align:center;">
                            {{ $qtyDisplay }}
                        </td>
                        <td style="border:1px solid #000; padding:4pt 5pt; text-align:center;">
                            {{ $subitem->satuan }}
                        </td>
                        <td style="border:1px solid #000; padding:4pt 6pt; text-align:right;">
                            Rp{{ number_format($subitem->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td style="border:1px solid #000; padding:4pt 6pt; text-align:right;">
                            Rp{{ number_format($jumlahHarga, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <tr>
            <td colspan="4"
                style="border:1px solid #000; padding:4pt 6pt; text-align:right; font-weight:bold;">
                TOTAL DANA KELUAR
            </td>
            <td style="border:1px solid #000; padding:4pt 6pt; text-align:right; font-weight:bold;">
                Rp{{ number_format($grandTotalKeluar, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>
@else
<p style="font-style:italic; color:#666; font-size:10pt;">Tidak ada data dana keluar.</p>
@endif

<div class="page-break"></div>

{{-- O. Penutup --}}
<p class="section-title">O. Penutup</p>
<div class="rich">{!! \App\Support\RichText::render($lpj->penutup) !!}</div>
