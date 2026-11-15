<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeighbridgeTicketResource\Pages;

use App\Filament\Resources\WeighbridgeTicketResource;
use Filament\Resources\Pages\ListRecords;

class ListWeighbridgeTickets extends ListRecords
{
    protected static string $resource = WeighbridgeTicketResource::class;
}
