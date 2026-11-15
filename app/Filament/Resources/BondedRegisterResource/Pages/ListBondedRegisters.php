<?php

declare(strict_types=1);

namespace App\Filament\Resources\BondedRegisterResource\Pages;

use App\Filament\Resources\BondedRegisterResource;
use Filament\Resources\Pages\ListRecords;

class ListBondedRegisters extends ListRecords
{
    protected static string $resource = BondedRegisterResource::class;
}
