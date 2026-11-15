<?php

declare(strict_types=1);

namespace App\Filament\Resources\EffluentLogResource\Pages;

use App\Filament\Resources\EffluentLogResource;
use Filament\Resources\Pages\ListRecords;

class ListEffluentLogs extends ListRecords
{
    protected static string $resource = EffluentLogResource::class;
}
