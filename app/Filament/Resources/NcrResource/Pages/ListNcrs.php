<?php

declare(strict_types=1);

namespace App\Filament\Resources\NcrResource\Pages;

use App\Filament\Resources\NcrResource;
use Filament\Resources\Pages\ListRecords;

class ListNcrs extends ListRecords
{
    protected static string $resource = NcrResource::class;
}
