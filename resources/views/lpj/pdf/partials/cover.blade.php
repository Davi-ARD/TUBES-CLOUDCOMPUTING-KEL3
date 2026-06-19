<div style="padding-top: 10pt;">

    {{-- Judul --}}
    <table width="100%" style="border:none; border-collapse:collapse;">
        <tr>
            <td align="center" style="border:none; text-align:center; padding:0;">
                <p style="font-weight: bold; font-size: 14pt; text-align: center; margin: 0 0 6pt 0; line-height: 1.3;">
                    LAPORAN PERTANGGUNGJAWABAN
                </p>
                <p style="font-weight: bold; font-size: 14pt; text-align: center; margin: 0 0 4pt 0; line-height: 1.3;">
                    {{ strtoupper($lpj->nama_kegiatan) }}
                </p>
                @if($lpj->akronim)
                <p style="font-weight: bold; font-size: 13pt; text-align: center; margin: 0 0 6pt 0; line-height: 1.3;">
                    ({{ strtoupper($lpj->akronim) }})
                </p>
                @endif
                <p style="font-size: 12pt; text-align: center; margin: 0; line-height: 1.3;">
                    {{ $lpj->tanggal_pelaksanaan }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Logo Telkom --}}
    <table width="100%" style="border:none; border-collapse:collapse; margin-top: 50pt;">
        <tr>
            <td align="center" style="border:none; text-align:center; padding:0;">
                @if(file_exists(public_path('img/logo-telkom-proposal.png')))
                    <img src="{{ public_path('img/logo-telkom-proposal.png') }}"
                         style="width: 170pt; height: auto;">
                @else
                    <p style="font-weight: bold; font-size: 16pt; margin: 40pt 0;">TELKOM UNIVERSITY</p>
                @endif
            </td>
        </tr>
    </table>

    {{-- Logo Organisasi --}}
    @if($lpj->logo_organisasi_path && file_exists(storage_path('app/public/' . $lpj->logo_organisasi_path)))
    <table width="100%" style="border:none; border-collapse:collapse; margin-top: 20pt;">
        <tr>
            <td align="center" style="border:none; text-align:center; padding:0;">
                <img src="{{ storage_path('app/public/' . $lpj->logo_organisasi_path) }}"
                     style="width: 200pt; height: auto;">
            </td>
        </tr>
    </table>
    @elseif($lpj->collabLogos->isEmpty())
    <div style="height: 140pt;"></div>
    @endif

    {{-- Logo UKM kolaborator (item #4): satu baris sejajar di bawah logo utama --}}
    @php
        $collabsLpj = $lpj->collabLogos->filter(fn ($c) => file_exists(storage_path('app/public/' . $c->file_path)))->values();
    @endphp
    @if($collabsLpj->isNotEmpty())
        <table width="100%" style="border:none; border-collapse:collapse; margin-top: 18pt; table-layout:fixed;">
            <tr>
                @foreach($collabsLpj as $collab)
                    <td align="center" style="border:none; text-align:center; padding:0 6pt; vertical-align:middle;">
                        <img src="{{ storage_path('app/public/' . $collab->file_path) }}"
                             style="max-width: 100%; max-height: 80pt; object-fit: contain;">
                    </td>
                @endforeach
            </tr>
        </table>
    @endif

    {{-- Nama UKM, Universitas Telkom, Kota, dan Tahun --}}
    <div style="position: fixed; bottom: 80px; left: 0; right: 0; text-align: center;">
        @if($lpj->penyelenggara)
        <p style="font-size: 11pt; font-weight: bold; text-align: center; margin: 0 0 2px 0; line-height: 1.3;">
            {{ strtoupper($lpj->penyelenggara) }}
        </p>
        @endif
        <p style="font-size: 11pt; font-weight: bold; text-align: center; margin: 0 0 2px 0; line-height: 1.3;">
            UNIVERSITAS TELKOM
        </p>
        <p style="font-size: 11pt; font-weight: bold; text-align: center; margin: 0 0 2px 0; line-height: 1.3;">
            {{ strtoupper($lpj->kota ?? 'BANDUNG') }}
        </p>
        <p style="font-size: 11pt; font-weight: bold; text-align: center; margin: 0; line-height: 1.3;">
            {{ $lpj->tahun ?? now()->year }}
        </p>
    </div>

</div>
