<?php

declare(strict_types=1);

namespace App\Filament\Resources\CycleCountResource\Pages;

use App\Filament\Resources\CycleCountResource;
use Filament\Resources\Pages\ListRecords;

class ListCycleCounts extends ListRecords
{
    protected static string $resource = CycleCountResource::class;
}
