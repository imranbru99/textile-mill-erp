<?php

declare(strict_types=1);

namespace App\Services\Formulas;

use App\Support\Decimal;

final class InventoryValuationService
{
    public function weightedAverage(string $oldQty, string $oldCost, string $inQty, string $inCost): string
    {
        $oldValue = Decimal::mul($oldQty, $oldCost, 6);
        $inValue = Decimal::mul($inQty, $inCost, 6);
        $totalQty = Decimal::add($oldQty, $inQty, 4);
        if (Decimal::cmp($totalQty, '0', 4) === 0) {
            return '0.000000';
        }

        return Decimal::div(Decimal::add($oldValue, $inValue, 6), $totalQty, 6);
    }

    /**
     * Consume oldest layers first.
     *
     * @param  list<array{id: int, qty: string, cost: string}>  $layers
     * @return array{layers: list<array{id: int, qty: string, cost: string}>, consumed: list<array{id: int, qty: string, cost: string}>, value: string}
     */
    public function fifoConsume(array $layers, string $qty): array
    {
        $remaining = Decimal::of($qty, 4);
        $value = '0.0000';
        $consumed = [];
        foreach ($layers as &$layer) {
            if (Decimal::cmp($remaining, '0', 4) <= 0) {
                break;
            }
            $take = Decimal::min($layer['qty'], $remaining, 4);
            if (Decimal::cmp($take, '0', 4) === 0) {
                continue;
            }
            $layer['qty'] = Decimal::sub($layer['qty'], $take, 4);
            $remaining = Decimal::sub($remaining, $take, 4);
            $line = Decimal::mul($take, $layer['cost'], 4);
            $value = Decimal::add($value, $line, 4);
            $consumed[] = ['id' => $layer['id'], 'qty' => $take, 'cost' => $layer['cost']];
        }
        unset($layer);

        if (Decimal::cmp($remaining, '0', 4) === 1) {
            throw new \RuntimeException('Insufficient stock for FIFO issue.');
        }

        return ['layers' => $layers, 'consumed' => $consumed, 'value' => $value];
    }

    /** @param array<string, string> $charges freight, duty, insurance, cnf */
    public function landedUnitCost(string $goodsValue, string $qty, array $charges): string
    {
        $total = $goodsValue;
        foreach ($charges as $amount) {
            $total = Decimal::add($total, $amount, 4);
        }

        return Decimal::div($total, $qty, 6);
    }
}
