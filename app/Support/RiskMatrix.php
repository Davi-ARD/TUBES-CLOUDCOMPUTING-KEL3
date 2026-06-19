<?php

namespace App\Support;

/**
 * Pemetaan Tingkat Risiko mengikuti RISK MATRIX GRADING standar Tel-U.
 *
 * Penilaian Risiko = Peluang (keterjadian) x Akibat (dampak), masing-masing
 * skala 1-5. Hasil perkalian dipetakan ke kategori (Kriteria Risiko):
 *   - LOW    : skor 1-2
 *   - MEDIUM : skor 3-9
 *   - HIGH   : skor 10-14
 *   - DANGER : skor >= 15
 *
 * Ambang ini mereproduksi seluruh sel matrix Tel-U dengan tepat.
 */
class RiskMatrix
{
    /**
     * @param  string|null  $peluang  format "n/5" (mis. "3/5")
     * @param  string|null  $akibat   format "n/5"
     */
    public static function grade(?string $peluang, ?string $akibat): string
    {
        $p = (int) explode('/', (string) $peluang)[0];
        $a = (int) explode('/', (string) $akibat)[0];
        $p = max(0, min(5, $p));
        $a = max(0, min(5, $a));

        $score = $p * $a;

        if ($score <= 0) {
            return '';
        }
        if ($score <= 2) {
            return 'LOW';
        }
        if ($score <= 9) {
            return 'MEDIUM';
        }
        if ($score <= 14) {
            return 'HIGH';
        }

        return 'DANGER';
    }
}
