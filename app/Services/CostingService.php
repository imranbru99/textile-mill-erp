<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Decimal;

final class CostingService
{
    /**
     * @param  list<array{name: string, amount: string}>  $elements
     * @return array{elements: list<array{name: string, amount: string}>, total: string, rate: string}
     */
    public function sheet(array $elements, string $qtyKg, string $marginPercent): array
    {
        $total = '0.0000';
        $normalised = [];
        foreach ($elements as $element) {
            $amount = Decimal::of($element['amount'], 4);
            $total = Decimal::add($total, $amount, 4);
            $normalised[] = ['name' => $element['name'], 'amount' => $amount];
        }
        $withMargin = Decimal::mul($total, Decimal::add('1', Decimal::div($marginPercent, '100', 6), 6), 4);
        $rate = Decimal::cmp($qtyKg, '0', 4) === 0 ? '0.000000' : Decimal::div($withMargin, $qtyKg, 6);

        return ['elements' => $normalised, 'total' => $total, 'quote_value' => $withMargin, 'rate' => $rate];
    }
}
