<div style="padding-top: 10pt;">

    {{-- Judul: table-based centering (dompdf tidak selalu hormati text-align:center pada block) --}}
    <table width="100%" style="border:none; border-collapse:collapse;">
        <tr>
            <td align="center" style="border:none; text-align:center; padding:0;">
                <p style="font-weight: bold; font-size: 14pt; text-align: center; margin: 0 0 6pt 0; line-height: 1.3;">
                    PROPOSAL KEGIATAN
                </p>
                <p style="font-weight: bold; font-size: 14pt; text-align: center; margin: 0 0 6pt 0; line-height: 1.3;">
                    {{ strtoupper($proposal->nama_kegiatan) }}
                </p>
                <p style="font-size: 12pt; text-align: center; margin: 0; line-height: 1.3;">
                    {{ $proposal->tanggal_pelaksanaan }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Logo Telkom: 170pt lebar, center via table --}}
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

    {{-- Logo Organisasi: 200pt lebar, center via table --}}
    @if($proposal->logo_organisasi_path && file_exists(storage_path('app/public/' . $proposal->logo_organisasi_path)))
    <table width="100%" style="border:none; border-collapse:collapse; margin-top: 20pt;">
        <tr>
            <td align="center" style="border:none; text-align:center; padding:0;">
                <img src="{{ storage_path('app/public/' . $proposal->logo_organisasi_path) }}"
                     style="width: 200pt; height: auto; max-height: 140pt; object-fit: contain;">
            </td>
        </tr>
    </table>
    @elseif($proposal->collabLogos->isEmpty())
    {{-- Spacer jika tidak ada logo UKM sama sekali --}}
    <div style="height: 140pt;"></div>
    @endif

    {{-- Logo UKM kolaborator (item #4): satu baris sejajar di bawah logo utama --}}
    @php
        $collabsProp = $proposal->collabLogos->filter(fn ($c) => file_exists(storage_path('app/public/' . $c->file_path)))->values();
    @endphp
    @if($collabsProp->isNotEmpty())
        <table width="100%" style="border:none; border-collapse:collapse; margin-top: 18pt; table-layout:fixed;">
            <tr>
                @foreach($collabsProp as $collab)
                    <td align="center" style="border:none; text-align:center; padding:0 6pt; vertical-align:middle;">
                        <img src="{{ storage_path('app/public/' . $collab->file_path) }}"
                             style="max-width: 100%; max-height: 80pt; object-fit: contain;">
                    </td>
                @endforeach
            </tr>
        </table>
    @endif

    {{-- BANDUNG dan Tahun: fixed di dekat bawah halaman cover, tidak ikut flow normal --}}
    <div style="position: fixed; bottom: 80px; left: 0; right: 0; text-align: center;">
        <p style="font-size: 12pt; font-weight: bold; text-align: center; margin: 0 0 4px 0;">
            {{ strtoupper($proposal->kota ?? 'BANDUNG') }}
        </p>
        <p style="font-size: 12pt; font-weight: bold; text-align: center; margin: 0;">
            {{ $proposal->tahun ?? now()->year }}
        </p>
    </div>

</div>
