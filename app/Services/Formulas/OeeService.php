<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

final class OeeService
{
    /** @return array{availability: string, performance: string, quality: string, oee: string} */
    public function calculate(
        string $plannedMinutes,
        string $downtimeMinutes,
        string $idealCycleSeconds,
        string $totalPieces,
        string $goodPieces,
    ): array {
        $run = Decimal::sub($plannedMinutes, $downtimeMinutes, 4);
        $availability = Decimal::div($run, $plannedMinutes, 4);
        $idealMinutes = Decimal::div(Decimal::mul($totalPieces, $idealCycleSeconds, 6), '60', 6);
        $performance = Decimal::cmp($run, '0', 4) === 0
            ? '0.0000'
            : Decimal::min('1', Decimal::div($idealMinutes, $run, 4), 4);
        $quality = Decimal::div($goodPieces, $totalPieces, 4);
        $oee = Decimal::mul(Decimal::mul($availability, $performance, 6), $quality, 4);

        return [
            'availability' => $availability,
            'performance' => $performance,
            'quality' => $quality,
            'oee' => $oee,
        ];
    }
}
