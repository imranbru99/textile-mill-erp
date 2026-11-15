<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Foundation\Models\Company;
use App\Modules\Inventory\Models\ItemLot;
use App\Modules\Inventory\Models\StockBalance;
use App\Modules\Inventory\Models\StockMovement;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Master\Models\Item;
use App\Services\Formulas\InventoryValuationService;
use App\Support\Decimal;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class StockService
{
    public function __construct(private readonly InventoryValuationService $valuation) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function receive(
        Item $item,
        int $warehouseId,
        string $qty,
        string $unitCost,
        string $lotNo,
        ?string $referenceType = null,
        ?int $referenceId = null,
        string $status = 'available',
        array $attributes = [],
    ): StockMovement {
        return DB::transaction(function () use ($item, $warehouseId, $qty, $unitCost, $lotNo, $referenceType, $referenceId, $status, $attributes): StockMovement {
            $lot = ItemLot::query()->firstOrNew([
                'company_id' => $item->company_id,
                'item_id' => $item->id,
                'warehouse_id' => $warehouseId,
                'lot_no' => $lotNo,
            ]);
            $lot->branch_id = $lot->branch_id ?: Warehouse::query()->whereKey($warehouseId)->value('branch_id');
            $lot->status = $status;
            $lot->received_at = $lot->received_at ?: now();
            $existing = $lot->getAttribute('attributes');
            $lot->setAttribute('attributes', array_merge(is_array($existing) ? $existing : [], $attributes));
            $company = Company::query()->findOrFail($item->company_id);
            $onHand = Decimal::of((string) ($lot->qty_on_hand ?? '0'), 4);
            $cost = (string) ($lot->unit_cost ?? '0');
            if ($company->valuation_method === 'weighted_average') {
                $lot->unit_cost = $this->valuation->weightedAverage($onHand, $cost, $qty, $unitCost);
            } else {
                $lot->unit_cost = Decimal::of($unitCost, 6);
            }
            $lot->qty_on_hand = Decimal::add($onHand, $qty, 4);
            $lot->save();

            $balance = $this->touchBalance($item, $lot, $warehouseId, $qty, $unitCost, 'in');

            return StockMovement::query()->create([
                'company_id' => $item->company_id,
                'branch_id' => $lot->branch_id,
                'item_id' => $item->id,
                'item_lot_id' => $lot->id,
                'warehouse_id' => $warehouseId,
                'direction' => 'in',
                'qty' => Decimal::of($qty, 4),
                'unit_cost' => $lot->unit_cost,
                'movement_type' => 'receipt',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'balance_after' => $balance,
                'transacted_at' => now(),
            ]);
        });
    }

    public function issue(
        Item $item,
        int $warehouseId,
        string $qty,
        ?int $lotId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        string $movementType = 'issue',
    ): StockMovement {
        return DB::transaction(function () use ($item, $warehouseId, $qty, $lotId, $referenceType, $referenceId, $movementType): StockMovement {
            $query = ItemLot::query()
                ->where('item_id', $item->id)
                ->where('warehouse_id', $warehouseId)
                ->where('status', 'available')
                ->where('qty_on_hand', '>', 0)
                ->orderBy('received_at');
            if ($lotId) {
                $query->whereKey($lotId);
            }
            $lots = $query->lockForUpdate()->get();
            if ($lots->isEmpty()) {
                throw new RuntimeException('No available lot for '.$item->code);
            }

            $company = Company::query()->findOrFail($item->company_id);
            $remaining = Decimal::of($qty, 4);
            $value = '0.0000';
            $lastLot = null;
            foreach ($lots as $lot) {
                if (Decimal::cmp($remaining, '0', 4) <= 0) {
                    break;
                }
                $take = Decimal::min((string) $lot->qty_on_hand, $remaining, 4);
                $cost = (string) $lot->unit_cost;
                $lot->qty_on_hand = Decimal::sub((string) $lot->qty_on_hand, $take, 4);
                $lot->save();
                $value = Decimal::add($value, Decimal::mul($take, $cost, 4), 4);
                $remaining = Decimal::sub($remaining, $take, 4);
                $lastLot = $lot;
                $this->touchBalance($item, $lot, $warehouseId, $take, $cost, 'out');
            }
            if (Decimal::cmp($remaining, '0', 4) === 1 || $lastLot === null) {
                throw new RuntimeException('Insufficient stock for '.$item->code);
            }

            $unit = $company->valuation_method === 'fifo'
                ? Decimal::div($value, $qty, 6)
                : (string) $lastLot->unit_cost;

            return StockMovement::query()->create([
                'company_id' => $item->company_id,
                'branch_id' => $lastLot->branch_id,
                'item_id' => $item->id,
                'item_lot_id' => $lastLot->id,
                'warehouse_id' => $warehouseId,
                'direction' => 'out',
                'qty' => Decimal::of($qty, 4),
                'unit_cost' => $unit,
                'movement_type' => $movementType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'balance_after' => (string) ItemLot::query()->where('item_id', $item->id)->where('warehouse_id', $warehouseId)->sum('qty_on_hand'),
                'transacted_at' => now(),
            ]);
        });
    }

    private function touchBalance(Item $item, ItemLot $lot, int $warehouseId, string $qty, string $cost, string $direction): string
    {
        $balance = StockBalance::query()->firstOrNew([
            'company_id' => $item->company_id,
            'item_id' => $item->id,
            'item_lot_id' => $lot->id,
            'warehouse_id' => $warehouseId,
        ]);
        $balance->branch_id = $lot->branch_id;
        $current = Decimal::of((string) ($balance->qty ?? '0'), 4);
        $currentValue = Decimal::of((string) ($balance->value ?? '0'), 4);
        if ($direction === 'in') {
            $balance->qty = Decimal::add($current, $qty, 4);
            $balance->value = Decimal::add($currentValue, Decimal::mul($qty, $cost, 4), 4);
        } else {
            $balance->qty = Decimal::sub($current, $qty, 4);
            $balance->value = Decimal::sub($currentValue, Decimal::mul($qty, $cost, 4), 4);
        }
        $balance->save();

        return (string) $balance->qty;
    }
}
