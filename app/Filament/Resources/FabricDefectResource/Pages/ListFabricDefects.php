<?php

declare(strict_types=1);

namespace App\Filament\Resources\FabricDefectResource\Pages;

use App\Filament\Resources\FabricDefectResource;
use Filament\Resources\Pages\ListRecords;

class ListFabricDefects extends ListRecords
{
    protected static string $resource = FabricDefectResource::class;
}
