<?php

declare(strict_types=1);

namespace App\Filament\Resources\CompanyModuleResource\Pages;

use App\Filament\Resources\CompanyModuleResource;
use Filament\Resources\Pages\EditRecord;

class EditCompanyModule extends EditRecord
{
    protected static string $resource = CompanyModuleResource::class;
}
