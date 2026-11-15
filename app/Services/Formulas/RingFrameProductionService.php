<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Ring-frame production in kilograms.
 *
 * kg = (RPM × 60 × hours) / (TPI × 36 × 840 × Ne) × 0.4536 × efficiency
 *
 * The 36 × 840 constants convert spindle inches of yarn into hanks (840 yards)
 * and then into pounds before the 0.4536 factor brings the result to kilograms.
 * Efficiency is a ratio. Values above 1 are treated as a percentage.
 */
final class RingFrameProductionService
{
    public function __construct(
        private readonly string $inchesPerYard = '36',
        private readonly string $yardsPerHank = '840',
        private readonly string $kgPerPound = '0.4536',
    ) {}

    public function kilograms(
        string $spindleRpm,
        string $hours,
        string $tpi,
        string $countNe,
        string $efficiency,
        int $spindles = 1,
    ): string {
        $efficiencyRatio = Decimal::gt($efficiency, '1')
            ? Decimal::div($efficiency, '100', 6)
            : Decimal::of($efficiency, 6);

        $numerator = Decimal::mul(Decimal::mul($spindleRpm, '60', 8), $hours, 8);
        $denominator = Decimal::mul(
            Decimal::mul(Decimal::mul($tpi, $this->inchesPerYard, 8), $this->yardsPerHank, 8),
            $countNe,
            8,
        );
        $pounds = Decimal::div($numerator, $denominator, 8);
        $perSpindle = Decimal::mul(Decimal::mul($pounds, $this->kgPerPound, 8), $efficiencyRatio, 8);

        return Decimal::round(Decimal::mul($perSpindle, (string) $spindles, 8), 4);
    }

    public function efficiency(string $actualKg, string $calculatedKgAtFullEfficiency): string
    {
        return Decimal::percent($actualKg, $calculatedKgAtFullEfficiency, 2);
    }

    /** Utilization kilograms per spindle for the shift. */
    public function ukg(string $outputKg, int $spindlesInstalled, string $hoursWorked, string $hoursAvailable): string
    {
        if ($spindlesInstalled <= 0) {
            return '0.0000';
        }

        $utilization = Decimal::div($hoursWorked, $hoursAvailable, 6);

        return Decimal::div(Decimal::mul($outputKg, $utilization, 6), (string) $spindlesInstalled, 4);
    }

    public function wastePercent(string $inputKg, string $outputKg): string
    {
        return Decimal::percent(Decimal::sub($inputKg, $outputKg, 4), $inputKg, 2);
    }

    public function yieldPercent(string $outputKg, string $inputKg): string
    {
        return Decimal::percent($outputKg, $inputKg, 2);
    }
}
