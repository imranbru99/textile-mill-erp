<?php

declare(strict_types=1);

namespace App\Filament\Resources\BreakdownLogResource\Pages;

use App\Filament\Resources\BreakdownLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBreakdownLog extends CreateRecord
{
    protected static string $resource = BreakdownLogResource::class;
}
