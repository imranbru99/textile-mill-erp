<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Yarn count conversions used on the mill floor.
 * Tex = 590.5 / Ne, Nm = 1.694 × Ne, Denier = 9 × Tex.
 */
final class CountConversionService
{
    public function __construct(
        private readonly string $texConstant = '590.5',
        private readonly string $nmFactor = '1.694',
        private readonly string $denierFactor = '9',
    ) {}

    public function neToTex(string $ne): string
    {
        return Decimal::div($this->texConstant, $ne, 4);
    }

    public function texToNe(string $tex): string
    {
        return Decimal::div($this->texConstant, $tex, 4);
    }

    public function neToNm(string $ne): string
    {
        return Decimal::mul($ne, $this->nmFactor, 4);
    }

    public function nmToNe(string $nm): string
    {
        return Decimal::div($nm, $this->nmFactor, 4);
    }

    public function texToDenier(string $tex): string
    {
        return Decimal::mul($tex, $this->denierFactor, 4);
    }

    public function denierToTex(string $denier): string
    {
        return Decimal::div($denier, $this->denierFactor, 4);
    }

    /**
     * Resultant English count of a blend: 100 / Σ(percentage / count).
     *
     * @param  array<int, array{percent: string, ne: string}>  $components
     */
    public function blendedNe(array $components): string
    {
        $sum = '0';
        foreach ($components as $component) {
            $sum = Decimal::add($sum, Decimal::div($component['percent'], $component['ne'], 8), 8);
        }

        return Decimal::div('100', $sum, 4);
    }
}
