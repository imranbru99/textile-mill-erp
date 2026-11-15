<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Scales a laboratory recipe to a bulk batch.
 * Chemical grams = fabric kg × 1000 × %owf / 100, or g/L × liquor litres.
 */
final class DyeRecipeService
{
    /** @param array<int, array{item_id: int, percent_owf: string, grams_per_liter: string}> $lines */
    public function scale(array $lines, string $batchKg, string $liquorRatio): array
    {
        $liquorLitres = Decimal::mul($batchKg, $liquorRatio, 4);
        $scaled = [];
        foreach ($lines as $line) {
            $fromOwf = Decimal::div(
                Decimal::mul(Decimal::mul($batchKg, '1000', 6), $line['percent_owf'], 6),
                '100',
                4,
            );
            $fromGpl = Decimal::mul($liquorLitres, $line['grams_per_liter'], 4);
            $grams = Decimal::cmp($line['percent_owf'], '0', 4) === 1 ? $fromOwf : $fromGpl;
            $scaled[] = [
                'item_id' => $line['item_id'],
                'grams' => $grams,
                'kg' => Decimal::div($grams, '1000', 4),
            ];
        }

        return [
            'liquor_litres' => $liquorLitres,
            'water_per_kg' => $liquorRatio,
            'lines' => $scaled,
        ];
    }

    public function rftPercent(int $rightFirstTime, int $totalBatches): string
    {
        return Decimal::percent((string) $rightFirstTime, (string) max($totalBatches, 0), 2);
    }

    public function redyePercent(int $redyed, int $totalBatches): string
    {
        return Decimal::percent((string) $redyed, (string) max($totalBatches, 0), 2);
    }
}
