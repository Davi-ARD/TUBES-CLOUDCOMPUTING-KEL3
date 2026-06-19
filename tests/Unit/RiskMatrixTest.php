<?php

namespace Tests\Unit;

use App\Support\RiskMatrix;
use PHPUnit\Framework\TestCase;

class RiskMatrixTest extends TestCase
{
    /**
     * Tingkat Risiko harus sesuai RISK MATRIX GRADING Tel-U
     * (Peluang x Akibat -> kategori).
     *
     * @dataProvider matrixCases
     */
    public function test_grade_matches_telu_matrix(string $peluang, string $akibat, string $expected): void
    {
        $this->assertSame($expected, RiskMatrix::grade($peluang, $akibat));
    }

    public static function matrixCases(): array
    {
        return [
            'LOW 1x1'        => ['1/5', '1/5', 'LOW'],
            'LOW 1x2'        => ['1/5', '2/5', 'LOW'],
            'LOW 2x1'        => ['2/5', '1/5', 'LOW'],
            'MEDIUM 1x3'     => ['1/5', '3/5', 'MEDIUM'],
            'MEDIUM 3x3'     => ['3/5', '3/5', 'MEDIUM'],
            'MEDIUM 5x1'     => ['5/5', '1/5', 'MEDIUM'],
            'MEDIUM 2x4'     => ['2/5', '4/5', 'MEDIUM'],
            'HIGH 2x5'       => ['2/5', '5/5', 'HIGH'],
            'HIGH 5x2'       => ['5/5', '2/5', 'HIGH'],
            'HIGH 3x4'       => ['3/5', '4/5', 'HIGH'],
            'HIGH 4x3'       => ['4/5', '3/5', 'HIGH'],
            'DANGER 3x5'     => ['3/5', '5/5', 'DANGER'],
            'DANGER 4x4'     => ['4/5', '4/5', 'DANGER'],
            'DANGER 5x5'     => ['5/5', '5/5', 'DANGER'],
        ];
    }

    public function test_grade_returns_empty_when_incomplete(): void
    {
        $this->assertSame('', RiskMatrix::grade('', '3/5'));
        $this->assertSame('', RiskMatrix::grade(null, null));
    }
}
