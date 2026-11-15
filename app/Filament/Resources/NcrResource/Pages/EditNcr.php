<?php

declare(strict_types=1);

namespace App\Filament\Resources\NcrResource\Pages;

use App\Filament\Resources\NcrResource;
use Filament\Resources\Pages\EditRecord;

class EditNcr extends EditRecord
{
    protected static string $resource = NcrResource::class;
}
