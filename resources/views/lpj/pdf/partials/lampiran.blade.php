@php
    $lampiran = $lpj->attachments
        ->whereNotIn('jenis', ['cover_logo', 'collab_logo'])
        ->sortBy('urutan');

    $hasPdf = $lampiran->contains(fn ($a) => str_contains((string) $a->file_type, 'pdf')
        || preg_match('/\.pdf$/i', $a->file_path));
@endphp

<p style="font-weight:bold; font-size:12pt; margin: 0 0 16pt 0;">Q. Lampiran</p>

@if($hasPdf)
    <p style="font-size:11pt; text-align:justify;">
        Dokumen lampiran terlampir pada halaman-halaman berikut dokumen ini.
    </p>
@else
    {{-- Lampiran lama berupa gambar (kompatibilitas data lama). --}}
    @php $counter = 0; @endphp
    @forelse($lampiran as $attachment)
        @php
            $counter++;
            $filePath = storage_path('app/public/' . $attachment->file_path);
            $isImage = str_starts_with((string) $attachment->file_type, 'image/')
                || preg_match('/\.(jpg|jpeg|png|gif)$/i', $attachment->file_path);
        @endphp
        @if(file_exists($filePath) && $isImage)
            <div style="margin-bottom:14pt; text-align:center; page-break-inside:avoid;">
                @if($attachment->caption)
                    <p style="font-size:10pt; margin:0 0 4pt 0; text-align:left;">{{ $attachment->caption }}</p>
                @endif
                <img src="{{ $filePath }}" style="max-width:420pt; max-height:300pt; display:inline-block; object-fit:contain;">
            </div>
        @endif
    @empty
        <p style="font-size:11pt; font-style:italic; color:#666;">Tidak ada lampiran.</p>
    @endforelse
@endif
