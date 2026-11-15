<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Loom production and yarn requirement from a fabric construction.
 * meters = RPM × 60 × minutes / PPI / 39.37 × efficiency.
 */
final class WeavingCalculator
{
    public function meters(string $rpm, string $minutes, string $ppi, string $efficiency): string
    {
        $ratio = Decimal::gt($efficiency, '1') ? Decimal::div($efficiency, '100', 6) : Decimal::of($efficiency, 6);
        $picks = Decimal::mul(Decimal::mul($rpm, '60', 4), $minutes, 4);
        $inches = Decimal::div($picks, $ppi, 6);
        $meters = Decimal::div($inches, '39.37', 6);

        return Decimal::mul($meters, $ratio, 4);
    }

    /**
     * Warp and weft yarn in kilograms for a beam or order length.
     *
     * @return array{warp_kg: string, weft_kg: string, total_kg: string}
     */
    public function yarnRequirement(
        string $lengthMeter,
        string $widthInch,
        string $epi,
        string $ppi,
        string $warpNe,
        string $weftNe,
        string $warpWastePercent = '3',
        string $weftWastePercent = '2',
        string $sizeAddonPercent = '8',
    ): array {
        $counts = new CountConversionService;
        $warpTex = $counts->neToTex($warpNe);
        $weftTex = $counts->neToTex($weftNe);
        $widthMeter = Decimal::div($widthInch, '39.37', 6);
        $ends = Decimal::mul($epi, $widthInch, 4);
        // grams = ends × length(m) × tex / 1000
        $warpGrams = Decimal::div(Decimal::mul(Decimal::mul($ends, $lengthMeter, 6), $warpTex, 6), '1000', 6);
        $picksPerMeter = Decimal::mul($ppi, '39.37', 4);
        $weftGrams = Decimal::div(
            Decimal::mul(Decimal::mul(Decimal::mul($picksPerMeter, $lengthMeter, 6), $widthMeter, 6), $weftTex, 6),
            '1000',
            6,
        );
        $warpKg = Decimal::div(Decimal::mul($warpGrams, Decimal::add('1', Decimal::div($warpWastePercent, '100', 6), 6), 6), '1000', 4);
        $weftKg = Decimal::div(Decimal::mul($weftGrams, Decimal::add('1', Decimal::div($weftWastePercent, '100', 6), 6), 6), '1000', 4);
        $sizedWarp = Decimal::mul($warpKg, Decimal::add('1', Decimal::div($sizeAddonPercent, '100', 6), 6), 4);

        return [
            'warp_kg' => $warpKg,
            'sized_warp_kg' => $sizedWarp,
            'weft_kg' => $weftKg,
            'total_kg' => Decimal::add($warpKg, $weftKg, 4),
        ];
    }
}
