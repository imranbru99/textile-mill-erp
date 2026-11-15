<?php

declare(strict_types=1);

namespace App\Filament\Resources\CapexRequestResource\Pages;

use App\Filament\Resources\CapexRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditCapexRequest extends EditRecord
{
    protected static string $resource = CapexRequestResource::class;
}
