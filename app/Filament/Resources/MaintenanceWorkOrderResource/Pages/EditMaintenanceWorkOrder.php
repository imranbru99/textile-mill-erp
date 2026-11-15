<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaintenanceWorkOrderResource\Pages;

use App\Filament\Resources\MaintenanceWorkOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceWorkOrder extends EditRecord
{
    protected static string $resource = MaintenanceWorkOrderResource::class;
}
