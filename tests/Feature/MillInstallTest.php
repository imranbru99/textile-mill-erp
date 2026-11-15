<?php

use App\Models\User;
use App\Modules\Finance\Models\JournalEntry;
use App\Modules\Master\Models\Item;
use App\Support\Decimal;
use Database\Seeders\MillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('installs a mill and walks an export order onto the public site', function () {
    $this->seed(MillSeeder::class);

    expect(Item::query()->count())->toBeGreaterThan(200);
    expect(User::query()->where('email', 'admin@padma-textile.test')->exists())->toBeTrue();

    $entry = JournalEntry::query()->with('journalLines')->first();
    $debit = '0';
    $credit = '0';
    foreach ($entry->journalLines as $line) {
        $debit = Decimal::add($debit, (string) $line->debit, 4);
        $credit = Decimal::add($credit, (string) $line->credit, 4);
    }
    expect($debit)->toBe($credit);

    $this->get('/')->assertOk();
    $this->get('/products')->assertOk();
    $this->post('/desk/login', [
        'email' => 'admin@padma-textile.test',
        'password' => 'password',
    ])->assertRedirect('/admin');
});
