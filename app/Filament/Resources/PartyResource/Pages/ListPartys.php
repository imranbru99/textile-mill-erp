<?php

declare(strict_types=1);

namespace App\Filament\Resources\PartyResource\Pages;

use App\Filament\Resources\PartyResource;
use Filament\Resources\Pages\ListRecords;

class ListPartys extends ListRecords
{
    protected static string $resource = PartyResource::class;
}
