<?php

declare(strict_types=1);

namespace App\Filament\Resources\StorageBinResource\Pages;

use App\Filament\Resources\StorageBinResource;
use Filament\Resources\Pages\ListRecords;

class ListStorageBins extends ListRecords
{
    protected static string $resource = StorageBinResource::class;
}
