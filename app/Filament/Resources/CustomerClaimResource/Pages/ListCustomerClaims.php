<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerClaimResource\Pages;

use App\Filament\Resources\CustomerClaimResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerClaims extends ListRecords
{
    protected static string $resource = CustomerClaimResource::class;
}
