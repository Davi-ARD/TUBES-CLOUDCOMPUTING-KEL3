<p style="font-weight:bold; font-size:12pt; margin: 0 0 16pt 0;">Q. Lampiran</p>

@php
    $hasPdf = $proposal->lampiranAttachments
        ->contains(fn ($a) => str_contains((string) $a->file_type, 'pdf')
            || preg_match('/\.pdf$/i', $a->file_path));
@endphp

@if($hasPdf)
    <p style="font-size:11pt; text-align:justify;">
        Dokumen lampiran terlampir pada halaman-halaman berikut dokumen ini.
    </p>
@else
    {{-- Lampiran lama berupa gambar (kompatibilitas data lama). --}}
    @forelse($proposal->lampiranAttachments as $i => $attachment)
        @php
            $filePath = storage_path('app/public/' . $attachment->file_path);
            $isImage = str_starts_with((string) $attachment->file_type, 'image/')
                || preg_match('/\.(jpg|jpeg|png|gif)$/i', $attachment->file_path);
        @endphp
        @if($i > 0 && $i % 2 === 0)
            <div style="page-break-before: always;"></div>
        @endif
        <div style="margin-bottom: 20pt; text-align: center; page-break-inside: avoid;">
            @if($attachment->caption)
                <p style="font-weight: bold; font-size: 11pt; margin: 0 0 6pt 0;">{{ $attachment->caption }}</p>
            @endif
            @if(file_exists($filePath) && $isImage)
                <img src="{{ $filePath }}" style="max-width:100%; max-height:380pt; object-fit:contain; display:inline-block;">
            @endif
        </div>
    @empty
        <p style="font-size:11pt; font-style:italic; color:#666;">Tidak ada lampiran.</p>
    @endforelse
@endif
