<?php

declare(strict_types=1);

namespace App\Filament\Resources\KnittingProductionResource\Pages;

use App\Filament\Resources\KnittingProductionResource;
use Filament\Resources\Pages\ListRecords;

class ListKnittingProductions extends ListRecords
{
    protected static string $resource = KnittingProductionResource::class;
}
