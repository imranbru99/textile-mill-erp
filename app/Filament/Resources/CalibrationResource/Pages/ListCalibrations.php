<?php

declare(strict_types=1);

namespace App\Filament\Resources\CalibrationResource\Pages;

use App\Filament\Resources\CalibrationResource;
use Filament\Resources\Pages\ListRecords;

class ListCalibrations extends ListRecords
{
    protected static string $resource = CalibrationResource::class;
}
