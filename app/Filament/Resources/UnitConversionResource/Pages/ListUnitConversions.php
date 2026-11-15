<?php

declare(strict_types=1);

namespace App\Filament\Resources\UnitConversionResource\Pages;

use App\Filament\Resources\UnitConversionResource;
use Filament\Resources\Pages\ListRecords;

class ListUnitConversions extends ListRecords
{
    protected static string $resource = UnitConversionResource::class;
}
