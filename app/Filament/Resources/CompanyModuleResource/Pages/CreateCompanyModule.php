<?php

declare(strict_types=1);

namespace App\Filament\Resources\CompanyModuleResource\Pages;

use App\Filament\Resources\CompanyModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyModule extends CreateRecord
{
    protected static string $resource = CompanyModuleResource::class;
}
