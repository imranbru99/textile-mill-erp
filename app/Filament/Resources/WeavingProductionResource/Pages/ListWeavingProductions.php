<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeavingProductionResource\Pages;

use App\Filament\Resources\WeavingProductionResource;
use Filament\Resources\Pages\ListRecords;

class ListWeavingProductions extends ListRecords
{
    protected static string $resource = WeavingProductionResource::class;
}
