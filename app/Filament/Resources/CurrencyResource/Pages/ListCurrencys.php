<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurrencyResource\Pages;

use App\Filament\Resources\CurrencyResource;
use Filament\Resources\Pages\ListRecords;

class ListCurrencys extends ListRecords
{
    protected static string $resource = CurrencyResource::class;
}
