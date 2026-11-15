<?php

declare(strict_types=1);

namespace App\Filament\Resources\SpinningProductionResource\Pages;

use App\Filament\Resources\SpinningProductionResource;
use Filament\Resources\Pages\ListRecords;

class ListSpinningProductions extends ListRecords
{
    protected static string $resource = SpinningProductionResource::class;
}
