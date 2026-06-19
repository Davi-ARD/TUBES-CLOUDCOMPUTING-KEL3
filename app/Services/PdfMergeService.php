<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Throwable;

/**
 * Menggabungkan PDF hasil dompdf dengan file PDF lampiran milik user.
 *
 * dompdf tidak bisa meng-embed PDF eksternal, jadi halaman PDF lampiran
 * di-append ke akhir dokumen memakai FPDI (parser PDF + FPDF sebagai writer).
 */
class PdfMergeService
{
    /**
     * Gabungkan dokumen utama dengan halaman dari file PDF lampiran.
     *
     * @param  string    $basePdfContent  Byte PDF mentah hasil dompdf.
     * @param  string[]  $pdfPaths        Path absolut file PDF lampiran (urutan dipertahankan).
     * @return string                     Byte PDF gabungan. Bila tak ada PDF valid untuk
     *                                     di-append, mengembalikan $basePdfContent apa adanya.
     */
    public function mergeAppend(string $basePdfContent, array $pdfPaths): string
    {
        $baseTmp = tempnam(sys_get_temp_dir(), 'ditmawa_pdf_');
        file_put_contents($baseTmp, $basePdfContent);

        try {
            $pdf = new Fpdi();

            // Dokumen utama (dompdf) — selalu bisa di-parse karena kita yang buat.
            $this->importAllPages($pdf, $baseTmp);

            // Lampiran PDF user — bisa gagal di-parse (mis. PDF v1.5+). Skip yang gagal.
            $appended = 0;
            foreach ($pdfPaths as $path) {
                if (!is_file($path)) {
                    continue;
                }

                try {
                    $this->importAllPages($pdf, $path);
                    $appended++;
                } catch (Throwable $e) {
                    report($e);
                }
            }

            // Tidak ada lampiran yang berhasil digabung — kembalikan dokumen asli.
            if ($appended === 0) {
                return $basePdfContent;
            }

            return (string) $pdf->Output('', 'S');
        } finally {
            @unlink($baseTmp);
        }
    }

    /**
     * Apakah ada minimal satu path PDF yang valid untuk di-merge.
     *
     * @param  string[]  $pdfPaths
     */
    public function hasMergeablePdf(array $pdfPaths): bool
    {
        foreach ($pdfPaths as $path) {
            if (is_file($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Impor seluruh halaman dari satu file PDF ke dokumen FPDI aktif,
     * mempertahankan ukuran & orientasi tiap halaman.
     */
    private function importAllPages(Fpdi $pdf, string $file): void
    {
        $pageCount = $pdf->setSourceFile($file);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $template = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($template);

            $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
        }
    }
}
