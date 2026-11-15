<?php

use App\Services\BondedBalanceService;
use App\Services\CostingService;
use App\Services\Formulas\CountConversionService;
use App\Services\Formulas\DyeRecipeService;
use App\Services\Formulas\InspectionService;
use App\Services\Formulas\OeeService;
use App\Services\Formulas\OvertimeService;
use App\Services\Formulas\RingFrameProductionService;
use App\Services\Formulas\WeavingCalculator;
use App\Services\MixingOptimizer;
use App\Support\BanglaCalendar;

it('converts yarn counts', function () {
    $counts = new CountConversionService;
    expect($counts->neToTex('30'))->toBe('19.6833');
    expect($counts->neToNm('30'))->toBe('50.8200');
});

it('calculates ring frame kilograms', function () {
    $kg = (new RingFrameProductionService)->kilograms('18000', '8', '20', '30', '0.90', 1);
    expect($kg)->toBe('0.1944');
});

it('calculates loom meters and dye grams', function () {
    $meters = (new WeavingCalculator)->meters('550', '480', '60', '1');
    expect($meters)->toStartWith('6705.');
    $scaled = (new DyeRecipeService)->scale([
        ['item_id' => 1, 'percent_owf' => '2.5', 'grams_per_liter' => '0'],
    ], '400', '8');
    expect($scaled['lines'][0]['kg'])->toBe('10.0000');
});

it('grades a four point roll and an AQL sample', function () {
    $inspection = new InspectionService;
    $points = $inspection->pointsPerHundredSquareYards('12', '100', '60');
    expect($inspection->grade($points))->toBe('A');
    expect((new App\Services\Formulas\AqlService)->plan(200, '2.5')['sample_size'])->toBe(32);
});

it('computes overtime, oee, costing, mixing and bonded waste', function () {
    $ot = (new OvertimeService)->settle('18000', 540, 0);
    expect($ot['blocked'])->toBeFalse();
    expect($ot['ot_minutes'])->toBe(60);
    $oee = (new OeeService)->calculate('480', '48', '1', '1000', '950');
    expect($oee['oee'])->not->toBe('0.0000');
    $sheet = (new CostingService)->sheet([['name' => 'cotton', 'amount' => '100']], '10', '10');
    expect($sheet['rate'])->toBe('11.000000');
    $mix = (new MixingOptimizer)->suggest([
        ['id' => 1, 'kg' => '100', 'micronaire' => '4.2', 'staple' => '28', 'strength' => '29'],
    ], ['micronaire' => '4.2', 'staple' => '28', 'strength' => '29'], '40');
    expect($mix['lines'][0]['kg'])->toBe('40.0000');
    $bond = (new BondedBalanceService)->reconcile('1000', '900', '850', '5');
    expect($bond['within_allowance'])->toBeTrue();
    $bangla = (new BanglaCalendar)->fromGregorian('2026-04-14');
    expect($bangla['year'])->toBe(1433);
    expect($bangla['day'])->toBe(1);
    expect($bangla['month'])->toBe(1);
});
