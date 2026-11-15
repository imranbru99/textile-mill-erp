<?php

declare(strict_types=1);

namespace App\Filament\Resources\TransporterResource\Pages;

use App\Filament\Resources\TransporterResource;
use Filament\Resources\Pages\ListRecords;

class ListTransporters extends ListRecords
{
    protected static string $resource = TransporterResource::class;
}
