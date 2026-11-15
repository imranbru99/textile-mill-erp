<?php

declare(strict_types=1);

namespace App\Filament\Resources\AccountMappingResource\Pages;

use App\Filament\Resources\AccountMappingResource;
use Filament\Resources\Pages\ListRecords;

class ListAccountMappings extends ListRecords
{
    protected static string $resource = AccountMappingResource::class;
}
