<p style="font-weight: bold; font-size: 13pt; text-align: center; margin-bottom: 12pt;">
    Lembar Evaluasi Proposal
</p>

{{-- TABEL INFO UTAMA: baris UKM/HIMA harus DI DALAM tabel (baris pertama) --}}
<table style="width:100%; border-collapse: collapse; font-size: 11pt;">
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; width: 35%; font-weight: bold;">
            UKM/HIMA/BEM/DPM/INDIVIDU/LAB
        </td>
        <td style="border:1px solid #000; padding: 5pt 8pt;">{{ $proposal->penyelenggara }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; font-weight: bold;">Kegiatan/Tema</td>
        <td style="border:1px solid #000; padding: 5pt 8pt;">
            {{ $proposal->nama_kegiatan }}{{ $proposal->tema_kegiatan ? ' / ' . $proposal->tema_kegiatan : '' }}
        </td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; font-weight: bold;">Tgl Pelaksanaan</td>
        <td style="border:1px solid #000; padding: 5pt 8pt;">{{ $proposal->tanggal_pelaksanaan }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; font-weight: bold;">Pelaksana</td>
        <td style="border:1px solid #000; padding: 5pt 8pt;">{{ $proposal->penyelenggara }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; font-weight: bold;">Tempat</td>
        <td style="border:1px solid #000; padding: 5pt 8pt;">{{ $proposal->tempat_kegiatan }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; font-weight: bold;">Tgl Evaluasi</td>
        <td style="border:1px solid #000; padding: 5pt 8pt; height: 20pt;">&nbsp;</td>
    </tr>
</table>

{{-- SEKSI 1: KEPALA URUSAN KEGIATAN MAHASISWA --}}
<p style="font-size: 11pt; font-weight: bold; margin: 14pt 0 4pt 0;">
    1. Kepala Urusan Kegiatan Mahasiswa
</p>
<p style="font-size: 11pt; margin: 0 0 4pt 0;">Pendanaan:</p>

{{-- Tabel Anggaran: header & nilai plain, left-aligned (BUKAN bold/center) --}}
<table style="width:100%; border-collapse: collapse; font-size: 10pt;">
    {{-- Header 3 kolom: plain, left --}}
    <tr>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; width: 33.33%;">Anggaran</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; width: 33.33%;">Diajukan</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; width: 33.33%;">Direkomendasikan</td>
    </tr>
    {{-- 3 cell Rp: left, top --}}
    <tr>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; vertical-align:top; height: 24pt;">Rp.</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; vertical-align:top;">Rp.</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:left; vertical-align:top;">Rp.</td>
    </tr>
</table>

<br>

{{-- Tabel Catatan & Tanda Tangan/Paraf: terpisah dari tabel Anggaran --}}
<table style="width:100%; border-collapse: collapse; font-size: 10pt;">
    <tr>
        <td colspan="2" style="border:1px solid #000; padding: 5pt 8pt; vertical-align: top; text-align:left; width: 66.66%; height: 60pt;">Catatan:</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:center; vertical-align: bottom; width: 33.33%;">
            <div style="font-size: 9pt; margin-bottom: 30pt;">Tanda Tangan/Paraf</div>
            <div style="font-weight: bold; font-size: 9pt;">Ahmad Adli, S.Kom., M.Kom</div>
        </td>
    </tr>
</table>

{{-- SEKSI 2: KEPALA BAGIAN PRESTASI DAN KEGIATAN MAHASISWA --}}
<p style="font-size: 11pt; font-weight: bold; margin: 14pt 0 4pt 0;">
    2. Kepala Bagian Prestasi dan Kegiatan Mahasiswa
</p>
<p style="font-size: 11pt; margin: 0 0 4pt 0;">Pendanaan:</p>

{{-- Tabel Disetujui: 1 kolom, rata kanan (margin-left:auto), tanpa kolom kosong --}}
<table style="width:35%; margin-left:auto; border-collapse:collapse; font-size: 10pt;">
    <tr>
        <td style="border:1px solid #000; padding:5px 8px; text-align:center;">Disetujui</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:5px 8px; text-align:left; height: 24pt;">Rp</td>
    </tr>
</table>

<br>

{{-- Tabel Catatan & paraf Ridwan Sukma: terpisah dari tabel Disetujui --}}
<table style="width:100%; border-collapse: collapse; font-size: 10pt;">
    <tr>
        <td style="border:1px solid #000; padding: 5pt 8pt; vertical-align: top; text-align:left; width: 66.66%; height: 60pt;">Catatan:</td>
        <td style="border:1px solid #000; padding: 5pt; text-align:center; vertical-align: bottom; width: 33.33%;">
            <div style="font-size: 9pt; margin-bottom: 30pt;">Tanda Tangan/Paraf</div>
            <div style="font-weight: bold; font-size: 9pt;">Ridwan Sukma AlBusyaeri, M.M., CRP</div>
        </td>
    </tr>
</table>
