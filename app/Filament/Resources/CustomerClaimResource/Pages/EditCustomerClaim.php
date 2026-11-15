<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerClaimResource\Pages;

use App\Filament\Resources\CustomerClaimResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomerClaim extends EditRecord
{
    protected static string $resource = CustomerClaimResource::class;
}
