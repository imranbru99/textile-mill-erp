<?php

declare(strict_types=1);

namespace App\Filament\Resources\AccountMappingResource\Pages;

use App\Filament\Resources\AccountMappingResource;
use Filament\Resources\Pages\EditRecord;

class EditAccountMapping extends EditRecord
{
    protected static string $resource = AccountMappingResource::class;
}
