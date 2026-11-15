<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubcontractOrderResource\Pages;

use App\Filament\Resources\SubcontractOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListSubcontractOrders extends ListRecords
{
    protected static string $resource = SubcontractOrderResource::class;
}
