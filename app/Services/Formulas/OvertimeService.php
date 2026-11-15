<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

/**
 * Bangladesh Labour Act overtime. Ordinary day is 8 hours, weekly ordinary
 * hours are 48, and total hours must not pass 60 without a special approval.
 * Overtime is paid at twice the basic hourly rate. Hourly rate = basic / (26 × 8),
 * the mill convention of 26 payable days, and it is configurable.
 */
final class OvertimeService
{
    public function __construct(
        private readonly int $ordinaryMinutesPerDay = 480,
        private readonly int $maxDailyMinutes = 600,
        private readonly int $maxWeeklyMinutes = 3600,
        private readonly string $otMultiplier = '2',
        private readonly string $divisorDays = '26',
        private readonly string $divisorHours = '8',
    ) {}

    /** @return array{ot_minutes: int, ot_pay: string, hourly: string, blocked: bool, reason: string|null} */
    public function settle(string $basicSalary, int $workedMinutes, int $weeklyMinutesAlready): array
    {
        $otMinutes = max(0, $workedMinutes - $this->ordinaryMinutesPerDay);
        $projected = $weeklyMinutesAlready + $workedMinutes;
        $blocked = $workedMinutes > $this->maxDailyMinutes || $projected > $this->maxWeeklyMinutes;
        $hourly = Decimal::div($basicSalary, Decimal::mul($this->divisorDays, $this->divisorHours, 4), 6);
        $otHours = Decimal::div((string) $otMinutes, '60', 4);
        $otPay = Decimal::mul(Decimal::mul($hourly, $this->otMultiplier, 6), $otHours, 2);

        return [
            'ot_minutes' => $otMinutes,
            'ot_pay' => $otPay,
            'hourly' => Decimal::round($hourly, 4),
            'blocked' => $blocked,
            'reason' => $blocked ? 'Overtime exceeds the Bangladesh Labour Act daily or weekly limit and needs approval.' : null,
        ];
    }
}
