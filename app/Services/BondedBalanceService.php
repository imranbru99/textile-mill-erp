<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Decimal;

/** Bonded warehouse balance: import − consumption − export, against the allowed wastage. */
final class BondedBalanceService
{
    /** @return array{remaining: string, waste_qty: string, within_allowance: bool} */
    public function reconcile(string $importQty, string $consumedQty, string $exportedQty, string $allowedWastePercent): array
    {
        $remaining = Decimal::sub(Decimal::sub($importQty, $consumedQty, 4), $exportedQty, 4);
        $waste = Decimal::sub($consumedQty, $exportedQty, 4);
        $allowed = Decimal::mul($importQty, Decimal::div($allowedWastePercent, '100', 6), 4);

        return [
            'remaining' => $remaining,
            'waste_qty' => $waste,
            'within_allowance' => Decimal::cmp($waste, $allowed, 4) <= 0,
        ];
    }
}
