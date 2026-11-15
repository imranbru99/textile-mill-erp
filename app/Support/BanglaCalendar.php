<?php

declare(strict_types=1);

namespace App\Support;

use DateTimeImmutable;
use Tarikh\Tarikh;

/**
 * Bangladesh revised Bangla calendar. Pohela Boishakh is 14 April.
 * The published tarikh package exposes Gregorian arithmetic only, so the
 * Bangla month map lives here and Tarikh handles the underlying date.
 */
final class BanglaCalendar
{
    /** @var list<string> */
    private const MONTHS = [
        'বৈশাখ', 'জ্যৈষ্ঠ', 'আষাঢ়', 'শ্রাবণ', 'ভাদ্র', 'আশ্বিন',
        'কার্তিক', 'অগ্রহায়ণ', 'পৌষ', 'মাঘ', 'ফাল্গুন', 'চৈত্র',
    ];

    /** @var list<string> */
    private const DIGITS = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    public function fromGregorian(string $date): array
    {
        $gregorian = Tarikh::parse($date)->toDateString();
        $dt = new DateTimeImmutable($gregorian);
        $year = (int) $dt->format('Y');
        $boishakh = new DateTimeImmutable($year.'-04-14');
        if ($dt < $boishakh) {
            $boishakh = new DateTimeImmutable(($year - 1).'-04-14');
        }
        $banglaYear = (int) $boishakh->format('Y') - 593;
        $dayIndex = (int) $boishakh->diff($dt)->days;
        $lengths = $this->lengths((int) $boishakh->format('Y') + 1);
        $month = 0;
        foreach ($lengths as $length) {
            if ($dayIndex < $length) {
                break;
            }
            $dayIndex -= $length;
            $month++;
        }

        return [
            'year' => $banglaYear,
            'month' => $month + 1,
            'day' => $dayIndex + 1,
            'month_name' => self::MONTHS[$month],
            'formatted' => $this->digits((string) ($dayIndex + 1)).' '.self::MONTHS[$month].' '.$this->digits((string) $banglaYear),
        ];
    }

    public function digits(string $number): string
    {
        return strtr($number, self::DIGITS);
    }

    /** @return list<int> */
    private function lengths(int $gregorianYearAfterBoishakh): array
    {
        $leap = (int) (new DateTimeImmutable($gregorianYearAfterBoishakh.'-01-01'))->format('L') === 1;

        return [31, 31, 31, 31, 31, 30, 30, 30, 30, 30, $leap ? 31 : 30, 30];
    }
}
