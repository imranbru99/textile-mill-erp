<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankStatementLineResource\Pages;

use App\Filament\Resources\BankStatementLineResource;
use Filament\Resources\Pages\ListRecords;

class ListBankStatementLines extends ListRecords
{
    protected static string $resource = BankStatementLineResource::class;
}
