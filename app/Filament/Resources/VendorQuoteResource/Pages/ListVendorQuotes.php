<?php

declare(strict_types=1);

namespace App\Filament\Resources\VendorQuoteResource\Pages;

use App\Filament\Resources\VendorQuoteResource;
use Filament\Resources\Pages\ListRecords;

class ListVendorQuotes extends ListRecords
{
    protected static string $resource = VendorQuoteResource::class;
}
