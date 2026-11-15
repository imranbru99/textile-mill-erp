<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankStatementLineResource\Pages;

use App\Filament\Resources\BankStatementLineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBankStatementLine extends CreateRecord
{
    protected static string $resource = BankStatementLineResource::class;
}
