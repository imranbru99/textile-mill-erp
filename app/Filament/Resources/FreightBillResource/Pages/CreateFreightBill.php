<?php

declare(strict_types=1);

namespace App\Filament\Resources\FreightBillResource\Pages;

use App\Filament\Resources\FreightBillResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFreightBill extends CreateRecord
{
    protected static string $resource = FreightBillResource::class;
}
