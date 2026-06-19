<?php

namespace App\Support;

use Mews\Purifier\Facades\Purifier;

/**
 * Penyaji konten rich text (HTML) untuk field paragraf naratif Proposal & LPJ.
 *
 * Field ini diisi lewat editor TinyMCE dan disimpan sebagai HTML. Sebelum
 * dicetak ke PDF dengan {!! !!}, konten WAJIB melewati helper ini agar:
 *   1. Aman dari XSS (whitelist ketat lewat HTMLPurifier config 'richtext').
 *   2. Kompatibel dengan data lama yang masih plain text: bila konten tidak
 *      mengandung tag HTML, baris baru ('\n') tetap dipertahankan via nl2br()
 *      sehingga tampilannya tidak berubah dari versi sebelumnya.
 *
 * Output hanya berisi tag sederhana (p, br, strong, em, u, ul, ol, li, a)
 * yang dirender dompdf dengan benar.
 */
class RichText
{
    /**
     * Deteksi tag HTML yang dihasilkan editor. Jika tidak ada, konten dianggap
     * plain text (data lama) dan diproses dengan nl2br untuk menjaga baris baru.
     */
    private const HTML_TAG_PATTERN = '/<(p|br|ul|ol|li|strong|b|em|i|u|a)\b[^>]*>/i';

    public static function render(?string $value): string
    {
        if ($value === null || trim($value) === '') {
            return '';
        }

        // Data lama (plain text) — pertahankan perilaku lama: escape + nl2br.
        if (! preg_match(self::HTML_TAG_PATTERN, $value)) {
            return nl2br(e($value));
        }

        // Konten editor — sanitasi dengan whitelist dompdf-safe.
        return Purifier::clean($value, 'richtext');
    }

    /**
     * Sanitasi saat simpan: bersihkan HTML editor dengan whitelist 'richtext'
     * sebelum masuk database (defense-in-depth). Mengembalikan null bila kosong.
     */
    public static function sanitize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return Purifier::clean($value, 'richtext');
    }
}
