<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaintenancePlanResource\Pages;

use App\Filament\Resources\MaintenancePlanResource;
use Filament\Resources\Pages\ListRecords;

class ListMaintenancePlans extends ListRecords
{
    protected static string $resource = MaintenancePlanResource::class;
}
