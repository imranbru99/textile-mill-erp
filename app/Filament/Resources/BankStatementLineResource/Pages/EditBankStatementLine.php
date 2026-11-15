<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankStatementLineResource\Pages;

use App\Filament\Resources\BankStatementLineResource;
use Filament\Resources\Pages\EditRecord;

class EditBankStatementLine extends EditRecord
{
    protected static string $resource = BankStatementLineResource::class;
}
