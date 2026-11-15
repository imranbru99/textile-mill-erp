<?php

declare(strict_types=1);

namespace App\Filament\Resources\FreightBillResource\Pages;

use App\Filament\Resources\FreightBillResource;
use Filament\Resources\Pages\ListRecords;

class ListFreightBills extends ListRecords
{
    protected static string $resource = FreightBillResource::class;
}
