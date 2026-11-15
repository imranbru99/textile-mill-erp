<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorQuoteResource\Pages;

use App\Filament\Resources\VendorQuoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorQuote extends CreateRecord
{
    protected static string $resource = VendorQuoteResource::class;
}
