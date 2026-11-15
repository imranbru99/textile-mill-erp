<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrintTemplateResource\Pages;

use App\Filament\Resources\PrintTemplateResource;
use Filament\Resources\Pages\EditRecord;

class EditPrintTemplate extends EditRecord
{
    protected static string $resource = PrintTemplateResource::class;
}
