<?php

declare(strict_types=1);

namespace App\Filament\Resources\RequestForQuoteResource\Pages;

use App\Filament\Resources\RequestForQuoteResource;
use Filament\Resources\Pages\EditRecord;

class EditRequestForQuote extends EditRecord
{
    protected static string $resource = RequestForQuoteResource::class;
}
