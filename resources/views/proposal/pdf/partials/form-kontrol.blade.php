{{-- Judul --}}
<p style="font-weight: bold; font-size: 12pt; text-align: center; margin: 0 0 8pt 0;">
    Form Kontrol Pengajuan Proposal
</p>

{{-- Header: Logo Telkom | DIREKTORAT KEMAHASISWAAN | Logo UKM --}}
<table style="width:100%; border-collapse: collapse; font-size: 10pt;">
    <tr>
        <td style="border:1px solid #000; padding: 4pt; width: 18%; text-align: center; vertical-align: middle;">
            @if(file_exists(public_path('img/logo-telkom-proposal.png')))
                <img src="{{ public_path('img/logo-telkom-proposal.png') }}" style="height: 28pt; width: auto;">
            @endif
        </td>
        <td style="border:1px solid #000; padding: 4pt; text-align: center; font-weight: bold; font-size: 11pt; vertical-align: middle;">
            DIREKTORAT KEMAHASISWAAN
        </td>
        <td style="border:1px solid #000; padding: 4pt; width: 18%; text-align: center; vertical-align: middle;">
            @if($proposal->logo_organisasi_path && file_exists(storage_path('app/public/' . $proposal->logo_organisasi_path)))
                <img src="{{ storage_path('app/public/' . $proposal->logo_organisasi_path) }}" style="height: 28pt; width: auto;">
            @else
                &nbsp;
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border:1px solid #000; padding: 5pt; text-align: center; font-weight: bold; font-size: 11pt;">
            FORM KONTROL PENGAJUAN PROPOSAL KEGIATAN
        </td>
    </tr>
</table>

{{-- Tabel utama: 5 kolom (Label | Sub | Tanggal | Catatan | Paraf) --}}
<table style="width:100%; border-collapse: collapse; table-layout: fixed; font-size: 10pt; page-break-inside: avoid;">
    <colgroup>
        <col style="width:15%">  {{-- Label utama --}}
        <col style="width:18%">  {{-- Sub-label / checkbox --}}
        <col style="width:13%">  {{-- Tanggal (lebih sempit) --}}
        <col style="width:44%">  {{-- Catatan (jauh lebih lebar) --}}
        <col style="width:10%">  {{-- Paraf --}}
    </colgroup>

    {{-- MASUK: Tanggal colspan=2 (isi kolom kosong), label pojok kiri atas --}}
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:top; text-align:left;"><strong>MASUK</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>

    {{-- KELAYAKAN PROPOSAL: rowspan 2, sub-label Ya/Tidak --}}
    <tr>
        <td rowspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:middle;"><strong>KELAYAKAN<br>PROPOSAL</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">
            <span style="display:inline-block; width:11pt; height:11pt; border:1pt solid #000; vertical-align:middle;"></span>&nbsp;Ya
        </td>
        <td colspan="2" rowspan="2" style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Revisi</span><div style="min-height:30px;"></div></td>
        <td rowspan="2" style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">
            <span style="display:inline-block; width:11pt; height:11pt; border:1pt solid #000; vertical-align:middle;"></span>&nbsp;Tidak
        </td>
    </tr>

    {{-- PRESENTASI: Tanggal colspan=2 (isi kolom kosong), label pojok kiri atas --}}
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:top; text-align:left;"><strong>PRESENTASI</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>

    {{-- ANGGARAN: 3 sel sejajar (TANPA colspan tunggal, biar sama lebar) --}}
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; text-align:center; vertical-align:middle;"><strong>ANGGARAN DIAJUKAN</strong></td>
        <td colspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; text-align:center; vertical-align:middle;"><strong>ANGGARAN DIREKOMENDASIKAN</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; text-align:center; vertical-align:middle; font-size:9pt;"><strong>ANGGARAN DISETUJUI</strong></td>
    </tr>

    {{-- PENGESAHAN: rowspan 2: Kepala Urusan + Kepala Bagian --}}
    <tr>
        <td rowspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:middle;"><strong>PENGESAHAN</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle; font-size:9.5pt;">Kepala Urusan Kegiatan Mahasiswa</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle; font-size:9.5pt;">Kepala Bagian Prestasi &amp; Kegiatan Mahasiswa</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>

    {{-- PENCAIRAN DANA: rowspan 4 --}}
    <tr>
        <td rowspan="4" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:middle;"><strong>PENCAIRAN DANA</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">Kwitansi</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">Pengajuan Keuangan</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">Pencairan Keuangan</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:middle;">Penyerahan Dana</td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>

    {{-- ARSIP: Tanggal colspan=2 (isi kolom kosong), label pojok kiri atas --}}
    <tr>
        <td colspan="2" style="border:1px solid #000; padding:4pt 6pt; font-weight:bold; vertical-align:top; text-align:left;"><strong>ARSIP</strong></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Tanggal</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Catatan</span><div style="min-height:30px;"></div></td>
        <td style="border:1px solid #000; padding:4pt 6pt; vertical-align:top; text-align:left;"><span style="font-size:10.5pt;">Paraf</span><div style="min-height:30px;"></div></td>
    </tr>

</table>
