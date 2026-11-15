<?php

declare(strict_types=1);

namespace App\Filament\Resources\CapexRequestResource\Pages;

use App\Filament\Resources\CapexRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListCapexRequests extends ListRecords
{
    protected static string $resource = CapexRequestResource::class;
}
