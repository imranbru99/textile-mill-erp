<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Decimal;

/** Groups dye lots light-to-dark and packs them into machine capacity before any AI explanation. */
final class BatchOptimizer
{
    /**
     * @param  list<array{id: string, shade: string, kg: string, lightness: string}>  $lots
     * @return list<array{machine: int, shades: list<string>, kg: string}>
     */
    public function plan(array $lots, string $machineCapacityKg, int $machines): array
    {
        usort($lots, fn (array $a, array $b): int => Decimal::cmp($b['lightness'], $a['lightness'], 4));
        $plan = [];
        $index = 0;
        foreach ($lots as $lot) {
            $placed = false;
            foreach ($plan as &$batch) {
                if ($batch['machine'] !== ($index % max($machines, 1)) + 1 && count($plan) >= $machines) {
                    continue;
                }
                $room = Decimal::sub($machineCapacityKg, $batch['kg'], 4);
                if (Decimal::cmp($room, $lot['kg'], 4) >= 0 && ($batch['shades'] === [] || end($batch['shades']) === $lot['shade'] || Decimal::cmp($lot['lightness'], '40', 4) <= 0)) {
                    $batch['kg'] = Decimal::add($batch['kg'], $lot['kg'], 4);
                    $batch['shades'][] = $lot['shade'];
                    $placed = true;
                    break;
                }
            }
            unset($batch);
            if (! $placed) {
                $index++;
                $plan[] = [
                    'machine' => (($index - 1) % max($machines, 1)) + 1,
                    'shades' => [$lot['shade']],
                    'kg' => Decimal::of($lot['kg'], 4),
                ];
            }
        }

        return $plan;
    }
}
