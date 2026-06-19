<h2 class="text-center bold" style="margin-bottom:16pt; font-size:12pt;">REKAP PROPOSAL</h2>

<table>
    <thead>
        <tr>
            <th style="width:5%;">No.</th>
            <th style="width:35%;">DATA</th>
            <th>DESKRIPSI</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td class="bold">Nama Kegiatan</td>
            <td>{{ $proposal->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td class="text-center">2</td>
            <td class="bold">Penyelenggara</td>
            <td>{{ $proposal->penyelenggara }}</td>
        </tr>
        <tr>
            <td class="text-center">3</td>
            <td class="bold">Afiliasi</td>
            <td>{{ $proposal->afiliasi }}</td>
        </tr>
        <tr>
            <td class="text-center">4</td>
            <td class="bold">Tempat Kegiatan</td>
            <td>{{ $proposal->tempat_kegiatan }}</td>
        </tr>
        <tr>
            <td class="text-center">5</td>
            <td class="bold">Jadwal Kegiatan</td>
            <td>{{ $proposal->tanggal_pelaksanaan }}</td>
        </tr>
        <tr>
            <td class="text-center">6</td>
            <td class="bold">Total Anggaran Kegiatan</td>
            <td>Rp{{ number_format($proposal->total_anggaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">7</td>
            <td class="bold">Pengajuan Dana Internal</td>
            <td>Rp{{ number_format($proposal->pengajuan_dana, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">8</td>
            <td class="bold">Bentuk Kegiatan</td>
            <td><div class="rich">{!! \App\Support\RichText::render($proposal->bentuk_kegiatan) !!}</div></td>
        </tr>
        <tr>
            <td class="text-center">9</td>
            <td class="bold">Target Kegiatan</td>
            <td><div class="rich">{!! \App\Support\RichText::render($proposal->sasaran_kegiatan) !!}</div></td>
        </tr>
    </tbody>
</table>
