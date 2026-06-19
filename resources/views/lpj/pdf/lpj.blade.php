<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LPJ - {{ $lpj->nama_kegiatan }}</title>
    <style>
        @page {
            margin: 2cm 2.5cm 2cm 3cm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0; padding: 0;
        }
        h1 { font-size: 14pt; font-weight: bold; text-align: center; }
        h2 { font-size: 12pt; font-weight: bold; }
        p  { text-align: justify; margin: 0 0 6pt 0; }
        .text-center { text-align: center !important; }
        .text-right  { text-align: right !important; }
        .bold { font-weight: bold; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10pt;
            font-size: 11pt;
        }
        table td, table th {
            border: 1px solid #000;
            padding: 4pt 6pt;
            vertical-align: top;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .no-border, .no-border td, .no-border th {
            border: none !important;
        }

        .page-break { page-break-after: always; }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 14pt 0 5pt 0;
        }

        ol { margin: 4pt 0 8pt 20pt; padding: 0; }
        ol li { margin-bottom: 3pt; text-align: justify; }

        /* Konten rich text (field naratif dari editor) */
        .rich { margin: 0 0 6pt 0; }
        .rich p { text-align: justify; margin: 0 0 6pt 0; }
        .rich ul, .rich ol { margin: 4pt 0 8pt 22pt; padding: 0; }
        .rich ul { list-style-type: disc; }
        .rich ol { list-style-type: decimal; }
        .rich li { margin-bottom: 3pt; text-align: justify; }
        .rich strong, .rich b { font-weight: bold; }
        .rich em, .rich i { font-style: italic; }
        .rich u { text-decoration: underline; }
        .rich a { color: #000; text-decoration: underline; }

        img { max-width: 100%; }

        tr { page-break-inside: avoid; }

        .currency { text-align: right; }

        .signature-box { height: 55pt; display: block; }

        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>

    @include('lpj.pdf.partials.cover')
    <div class="page-break"></div>

    @include('lpj.pdf.partials.daftar-isi')
    <div class="page-break"></div>

    @include('lpj.pdf.partials.isi')

    {{-- Tanpa page-break di sini: heading "P. Lembar Pengesahan" sudah memakai
         page-break-before sendiri, sehingga break ganda akan membuat halaman kosong. --}}
    @include('lpj.pdf.partials.lembar-pengesahan')

    @php
        $lampiranPdf = $lpj->attachments->whereIn('jenis', ['lampiran', 'nota', 'bukti_transfer', 'dokumentasi', 'poster', 'lainnya']);
    @endphp
    @if($lampiranPdf->isNotEmpty())
        <div class="page-break"></div>
        @include('lpj.pdf.partials.lampiran', ['lampiranItems' => $lampiranPdf])
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                if ($PAGE_NUM > 1) {
                    $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                    $size = 9;
                    $text = (string) $PAGE_NUM;
                    $width = $fontMetrics->get_text_width($text, $font, $size);
                    $x = ($pdf->get_width() - $width) / 2;
                    $y = $pdf->get_height() - 30;
                    $pdf->text($x, $y, $text, $font, $size);
                }
            ');
        }
    </script>

</body>
</html>
