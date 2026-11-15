<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

/**
 * Fixed-point arithmetic for mill quantities and money. Never uses binary floats.
 */
final class Decimal
{
    public static function of(string|int|float $value, int $scale = 6): string
    {
        if (is_float($value)) {
            $value = sprintf('%.8F', $value);
        }

        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_numeric($value)) {
            throw new InvalidArgumentException('Decimal value must be numeric.');
        }

        return bcadd($value, '0', $scale);
    }

    public static function add(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        return bcadd(self::of($a, $scale), self::of($b, $scale), $scale);
    }

    public static function sub(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        return bcsub(self::of($a, $scale), self::of($b, $scale), $scale);
    }

    public static function mul(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        return bcmul(self::of($a, $scale + 2), self::of($b, $scale + 2), $scale);
    }

    public static function div(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        $divisor = self::of($b, $scale + 2);
        if (bccomp($divisor, '0', $scale + 2) === 0) {
            throw new InvalidArgumentException('Division by zero.');
        }

        return bcdiv(self::of($a, $scale + 2), $divisor, $scale);
    }

    public static function cmp(string|int|float $a, string|int|float $b, int $scale = 6): int
    {
        return bccomp(self::of($a, $scale), self::of($b, $scale), $scale);
    }

    public static function gt(string|int|float $a, string|int|float $b, int $scale = 6): bool
    {
        return self::cmp($a, $b, $scale) === 1;
    }

    public static function gte(string|int|float $a, string|int|float $b, int $scale = 6): bool
    {
        return self::cmp($a, $b, $scale) >= 0;
    }

    public static function min(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        return self::cmp($a, $b, $scale) <= 0 ? self::of($a, $scale) : self::of($b, $scale);
    }

    public static function max(string|int|float $a, string|int|float $b, int $scale = 6): string
    {
        return self::cmp($a, $b, $scale) >= 0 ? self::of($a, $scale) : self::of($b, $scale);
    }

    public static function percent(string|int|float $part, string|int|float $whole, int $scale = 4): string
    {
        if (self::cmp($whole, '0', $scale) === 0) {
            return self::of('0', $scale);
        }

        return self::mul(self::div($part, $whole, $scale + 2), '100', $scale);
    }

    public static function round(string|int|float $value, int $scale = 2): string
    {
        $working = self::of($value, $scale + 2);
        $factor = bcpow('10', (string) $scale, 0);
        $shifted = bcmul($working, $factor, 2);
        $sign = str_starts_with($shifted, '-') ? '-' : '';
        $absolute = ltrim($shifted, '-');
        [$whole, $fraction] = array_pad(explode('.', $absolute, 2), 2, '0');
        if ((int) substr($fraction, 0, 1) >= 5) {
            $whole = bcadd($whole, '1', 0);
        }

        return self::div($sign.$whole, $factor, $scale);
    }
}
