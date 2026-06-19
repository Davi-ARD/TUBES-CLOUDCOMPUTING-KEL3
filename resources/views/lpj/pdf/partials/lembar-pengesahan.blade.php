<p style="font-weight:bold; font-size:12pt; margin: 16pt 0 10pt 0; page-break-before: always;">P. Lembar Pengesahan</p>

<div style="page-break-inside: avoid;">

    {{-- BARIS 1: Ketua UKM (kiri) + Ketua Pelaksana (kanan) --}}
    <table style="width:100%; table-layout:fixed; border:none; border-collapse: collapse;">
        <tr>
            <td style="border:none; text-align:center; width:50%; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                Ketua {{ $lpj->penyelenggara ?? 'UKM' }}
            </td>
            <td style="border:none; text-align:center; width:50%; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                Ketua Pelaksana
            </td>
        </tr>
        {{-- Ruang TTD kosong --}}
        <tr>
            <td style="border:none; height:55pt;">&nbsp;</td>
            <td style="border:none; height:55pt;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                <strong>{{ $lpj->ketua_ukm_nama }}</strong><br>
                NIM. {{ $lpj->ketua_ukm_nim }}
            </td>
            <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                <strong>{{ $lpj->ketua_pelaksana_nama }}</strong><br>
                NIM. {{ $lpj->ketua_pelaksana_nim }}
            </td>
        </tr>
    </table>

    {{-- "Mengetahui," — terpusat --}}
    <p style="text-align:center; font-size:10.5pt; margin: 32pt 0 3pt 0;">Mengetahui,</p>

    {{-- BARIS 2: Pembina I (kiri) + Pembina II (kanan) --}}
    @if($lpj->pembina_2_nama)
        <table style="width:100%; table-layout:fixed; border:none; border-collapse: collapse;">
            <tr>
                <td style="border:none; text-align:center; width:50%; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                    Pembina I
                </td>
                <td style="border:none; text-align:center; width:50%; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                    Pembina II
                </td>
            </tr>
            {{-- Ruang TTD kosong --}}
            <tr>
                <td style="border:none; height:55pt;">&nbsp;</td>
                <td style="border:none; height:55pt;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                    <strong>{{ $lpj->pembina_1_nama }}</strong><br>
                    NIP. {{ $lpj->pembina_1_nip }}
                </td>
                <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                    <strong>{{ $lpj->pembina_2_nama }}</strong><br>
                    NIP. {{ $lpj->pembina_2_nip }}
                </td>
            </tr>
        </table>
    @else
        {{-- Hanya satu pembina: tampilkan terpusat --}}
        <table style="width:100%; table-layout:fixed; border:none; border-collapse: collapse;">
            <tr>
                <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                    Pembina
                </td>
            </tr>
            <tr>
                <td style="border:none; height:55pt;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                    <strong>{{ $lpj->pembina_1_nama }}</strong><br>
                    NIP. {{ $lpj->pembina_1_nip }}
                </td>
            </tr>
        </table>
    @endif

    {{-- Spasi sebelum bagian Menyetujui --}}
    <div style="height:40pt;"></div>

    {{-- BARIS 3: Menyetujui / Direktur — terpusat, satu kolom penuh --}}
    <table style="width:100%; table-layout:fixed; border:none; border-collapse: collapse;">
        <tr>
            <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt 3pt 4pt;">
                Menyetujui,<br>Direktur Kemahasiswaan, Karier dan Alumni
            </td>
        </tr>
        {{-- Ruang TTD kosong --}}
        <tr>
            <td style="border:none; height:55pt;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border:none; text-align:center; font-size:10.5pt; padding:0 4pt;">
                <strong>{{ $lpj->direktur_nama }}</strong><br>
                NIP. {{ $lpj->direktur_nip }}
            </td>
        </tr>
    </table>

</div>
