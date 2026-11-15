<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaintenanceWorkOrderResource\Pages;

use App\Filament\Resources\MaintenanceWorkOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceWorkOrders extends ListRecords
{
    protected static string $resource = MaintenanceWorkOrderResource::class;
}
