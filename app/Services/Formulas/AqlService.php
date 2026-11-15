<?php

declare(strict_types=1);

namespace App\Services\Formulas;

/**
 * ISO 2859-1 / ANSI Z1.4 single sampling, general inspection level II,
 * normal severity. Covers the AQL values a mill QC room actually uses.
 */
final class AqlService
{
    /** @var array<int, array{0: int, 1: string}> lot max => [sample, code] */
    private const LEVEL_II = [
        8 => [2, 'A'],
        15 => [3, 'B'],
        25 => [5, 'C'],
        50 => [8, 'D'],
        90 => [13, 'E'],
        150 => [20, 'F'],
        280 => [32, 'G'],
        500 => [50, 'H'],
        1200 => [80, 'J'],
        3200 => [125, 'K'],
        10000 => [200, 'L'],
        35000 => [315, 'M'],
        150000 => [500, 'N'],
        500000 => [800, 'P'],
        PHP_INT_MAX => [1250, 'Q'],
    ];

    /** code => aql => [accept, reject] */
    private const PLAN = [
        'A' => ['1.0' => [0, 1], '1.5' => [0, 1], '2.5' => [0, 1], '4.0' => [0, 1]],
        'B' => ['1.0' => [0, 1], '1.5' => [0, 1], '2.5' => [0, 1], '4.0' => [0, 1]],
        'C' => ['1.0' => [0, 1], '1.5' => [0, 1], '2.5' => [0, 1], '4.0' => [0, 1]],
        'D' => ['1.0' => [0, 1], '1.5' => [0, 1], '2.5' => [0, 1], '4.0' => [1, 2]],
        'E' => ['1.0' => [0, 1], '1.5' => [0, 1], '2.5' => [1, 2], '4.0' => [1, 2]],
        'F' => ['1.0' => [0, 1], '1.5' => [1, 2], '2.5' => [1, 2], '4.0' => [2, 3]],
        'G' => ['1.0' => [1, 2], '1.5' => [1, 2], '2.5' => [2, 3], '4.0' => [3, 4]],
        'H' => ['1.0' => [1, 2], '1.5' => [2, 3], '2.5' => [3, 4], '4.0' => [5, 6]],
        'J' => ['1.0' => [2, 3], '1.5' => [3, 4], '2.5' => [5, 6], '4.0' => [7, 8]],
        'K' => ['1.0' => [3, 4], '1.5' => [5, 6], '2.5' => [7, 8], '4.0' => [10, 11]],
        'L' => ['1.0' => [5, 6], '1.5' => [7, 8], '2.5' => [10, 11], '4.0' => [14, 15]],
        'M' => ['1.0' => [7, 8], '1.5' => [10, 11], '2.5' => [14, 15], '4.0' => [21, 22]],
        'N' => ['1.0' => [10, 11], '1.5' => [14, 15], '2.5' => [21, 22], '4.0' => [21, 22]],
        'P' => ['1.0' => [14, 15], '1.5' => [21, 22], '2.5' => [21, 22], '4.0' => [21, 22]],
        'Q' => ['1.0' => [21, 22], '1.5' => [21, 22], '2.5' => [21, 22], '4.0' => [21, 22]],
    ];

    /** @return array{code: string, sample_size: int, accept: int, reject: int, aql: string} */
    public function plan(int $lotSize, string $aql = '2.5'): array
    {
        $aql = number_format((float) $aql, 1, '.', '');
        if (! isset(self::PLAN['A'][$aql])) {
            $aql = '2.5';
        }

        $sample = 2;
        $code = 'A';
        foreach (self::LEVEL_II as $max => [$size, $letter]) {
            $sample = $size;
            $code = $letter;
            if ($lotSize <= $max) {
                break;
            }
        }

        [$accept, $reject] = self::PLAN[$code][$aql];

        return [
            'code' => $code,
            'sample_size' => min($sample, max($lotSize, 1)),
            'accept' => $accept,
            'reject' => $reject,
            'aql' => $aql,
        ];
    }

    public function decision(int $defects, int $accept, int $reject): string
    {
        if ($defects <= $accept) {
            return 'accept';
        }
        if ($defects >= $reject) {
            return 'reject';
        }

        return 'hold';
    }
}
