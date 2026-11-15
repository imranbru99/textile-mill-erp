<?php

declare(strict_types=1);

namespace App\Filament\Resources\BreakdownLogResource\Pages;

use App\Filament\Resources\BreakdownLogResource;
use Filament\Resources\Pages\EditRecord;

class EditBreakdownLog extends EditRecord
{
    protected static string $resource = BreakdownLogResource::class;
}
