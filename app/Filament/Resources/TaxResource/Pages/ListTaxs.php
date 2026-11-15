<?php

declare(strict_types=1);

namespace App\Filament\Resources\TaxResource\Pages;

use App\Filament\Resources\TaxResource;
use Filament\Resources\Pages\ListRecords;

class ListTaxs extends ListRecords
{
    protected static string $resource = TaxResource::class;
}
