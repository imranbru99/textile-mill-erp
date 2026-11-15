<?php

declare(strict_types=1);

namespace App\Filament\Resources\VaultDocumentResource\Pages;

use App\Filament\Resources\VaultDocumentResource;
use Filament\Resources\Pages\EditRecord;

class EditVaultDocument extends EditRecord
{
    protected static string $resource = VaultDocumentResource::class;
}
