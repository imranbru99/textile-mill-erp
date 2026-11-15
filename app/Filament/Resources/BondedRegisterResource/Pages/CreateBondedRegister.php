<?php

declare(strict_types=1);

namespace App\Filament\Resources\BondedRegisterResource\Pages;

use App\Filament\Resources\BondedRegisterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBondedRegister extends CreateRecord
{
    protected static string $resource = BondedRegisterResource::class;
}
