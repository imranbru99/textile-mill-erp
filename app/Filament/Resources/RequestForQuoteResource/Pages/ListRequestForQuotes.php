<?php

declare(strict_types=1);

namespace App\Filament\Resources\RequestForQuoteResource\Pages;

use App\Filament\Resources\RequestForQuoteResource;
use Filament\Resources\Pages\ListRecords;

class ListRequestForQuotes extends ListRecords
{
    protected static string $resource = RequestForQuoteResource::class;
}
