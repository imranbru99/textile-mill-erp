<?php

declare(strict_types=1);

namespace App\Filament\Resources\VaultDocumentResource\Pages;

use App\Filament\Resources\VaultDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVaultDocument extends CreateRecord
{
    protected static string $resource = VaultDocumentResource::class;
}
