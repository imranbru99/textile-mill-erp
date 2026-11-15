<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Decimal;

/** Rule-based cotton laydown. Picks bales whose fiber properties sit closest to the yarn target. */
final class MixingOptimizer
{
    /**
     * @param  list<array{id: int, kg: string, micronaire: string, staple: string, strength: string}>  $bales
     * @param  array{micronaire: string, staple: string, strength: string}  $target
     * @return array{lines: list<array{id: int, kg: string}>, predicted: array{micronaire: string, staple: string, strength: string}, explanation: string}
     */
    public function suggest(array $bales, array $target, string $laydownKg): array
    {
        $usable = array_values(array_filter($bales, fn (array $bale): bool => Decimal::cmp($bale['kg'], '0', 4) === 1));
        usort($usable, function (array $a, array $b) use ($target): int {
            return Decimal::cmp($this->distance($a, $target), $this->distance($b, $target), 6);
        });

        $left = Decimal::of($laydownKg, 4);
        $lines = [];
        foreach ($usable as $bale) {
            if (Decimal::cmp($left, '0', 4) <= 0) {
                break;
            }
            $take = Decimal::min($bale['kg'], $left, 4);
            $lines[] = ['id' => $bale['id'], 'kg' => $take, 'micronaire' => $bale['micronaire'], 'staple' => $bale['staple'], 'strength' => $bale['strength']];
            $left = Decimal::sub($left, $take, 4);
        }

        $predicted = $this->weighted($lines);

        return [
            'lines' => array_map(fn (array $line): array => ['id' => $line['id'], 'kg' => $line['kg']], $lines),
            'predicted' => $predicted,
            'explanation' => sprintf(
                'Laydown of %s kg from %d bales. Predicted micronaire %s, staple %s mm, strength %s.',
                $laydownKg,
                count($lines),
                $predicted['micronaire'],
                $predicted['staple'],
                $predicted['strength'],
            ),
        ];
    }

    /** @param array{micronaire: string, staple: string, strength: string} $bale
     * @param array{micronaire: string, staple: string, strength: string} $target */
    private function distance(array $bale, array $target): string
    {
        $mic = Decimal::sub($bale['micronaire'], $target['micronaire'], 4);
        $staple = Decimal::div(Decimal::sub($bale['staple'], $target['staple'], 4), '10', 4);
        $strength = Decimal::div(Decimal::sub($bale['strength'], $target['strength'], 4), '10', 4);

        return Decimal::add(Decimal::add(Decimal::mul($mic, $mic, 6), Decimal::mul($staple, $staple, 6), 6), Decimal::mul($strength, $strength, 6), 6);
    }

    /** @param list<array{kg: string, micronaire: string, staple: string, strength: string}> $lines
     * @return array{micronaire: string, staple: string, strength: string} */
    private function weighted(array $lines): array
    {
        $total = '0';
        $mic = '0';
        $staple = '0';
        $strength = '0';
        foreach ($lines as $line) {
            $total = Decimal::add($total, $line['kg'], 4);
            $mic = Decimal::add($mic, Decimal::mul($line['kg'], $line['micronaire'], 6), 6);
            $staple = Decimal::add($staple, Decimal::mul($line['kg'], $line['staple'], 6), 6);
            $strength = Decimal::add($strength, Decimal::mul($line['kg'], $line['strength'], 6), 6);
        }
        if (Decimal::cmp($total, '0', 4) === 0) {
            return ['micronaire' => '0.0000', 'staple' => '0.0000', 'strength' => '0.0000'];
        }

        return [
            'micronaire' => Decimal::div($mic, $total, 4),
            'staple' => Decimal::div($staple, $total, 4),
            'strength' => Decimal::div($strength, $total, 4),
        ];
    }
}
