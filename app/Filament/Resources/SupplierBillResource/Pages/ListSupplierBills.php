<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierBillResource\Pages;

use App\Filament\Resources\SupplierBillResource;
use Filament\Resources\Pages\ListRecords;

class ListSupplierBills extends ListRecords
{
    protected static string $resource = SupplierBillResource::class;
}
