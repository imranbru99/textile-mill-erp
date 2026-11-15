<?php

declare(strict_types=1);

namespace App\Filament\Resources\LabDipResource\Pages;

use App\Filament\Resources\LabDipResource;
use Filament\Resources\Pages\ListRecords;

class ListLabDips extends ListRecords
{
    protected static string $resource = LabDipResource::class;
}
