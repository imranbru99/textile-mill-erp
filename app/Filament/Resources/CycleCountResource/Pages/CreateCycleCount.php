<?php

declare(strict_types=1);

namespace App\Filament\Resources\CycleCountResource\Pages;

use App\Filament\Resources\CycleCountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCycleCount extends CreateRecord
{
    protected static string $resource = CycleCountResource::class;
}
