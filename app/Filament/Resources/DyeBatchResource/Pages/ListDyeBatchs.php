<?php

declare(strict_types=1);

namespace App\Filament\Resources\DyeBatchResource\Pages;

use App\Filament\Resources\DyeBatchResource;
use Filament\Resources\Pages\ListRecords;

class ListDyeBatchs extends ListRecords
{
    protected static string $resource = DyeBatchResource::class;
}
