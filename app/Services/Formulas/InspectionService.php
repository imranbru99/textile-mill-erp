<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Four-point fabric inspection.
 * points per 100 sq.yd = (points × 36 × 100) / (length yards × width inches).
 */
final class InspectionService
{
    /** @param array<string, string> $thresholds grade => max points per 100 sq yd */
    public function __construct(
        private readonly array $thresholds = ['A' => '20', 'B' => '28', 'C' => '40'],
    ) {}

    public function pointsPerHundredSquareYards(string $points, string $lengthMeter, string $widthInch): string
    {
        $lengthYards = Decimal::div($lengthMeter, '0.9144', 6);
        $denominator = Decimal::mul($lengthYards, $widthInch, 6);
        $numerator = Decimal::mul(Decimal::mul($points, '36', 4), '100', 4);

        return Decimal::div($numerator, $denominator, 2);
    }

    public function grade(string $pointsPerHundred): string
    {
        foreach ($this->thresholds as $grade => $limit) {
            if (Decimal::cmp($pointsPerHundred, $limit, 2) <= 0) {
                return $grade;
            }
        }

        return 'reject';
    }
}
