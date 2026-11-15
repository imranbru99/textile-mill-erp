<?php

declare(strict_types=1);

namespace App\Filament\Resources\ItemLotResource\Pages;

use App\Filament\Resources\ItemLotResource;
use Filament\Resources\Pages\ListRecords;

class ListItemLots extends ListRecords
{
    protected static string $resource = ItemLotResource::class;
}
