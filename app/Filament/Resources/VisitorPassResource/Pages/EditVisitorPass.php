<?php

declare(strict_types=1);

namespace App\Filament\Resources\VisitorPassResource\Pages;

use App\Filament\Resources\VisitorPassResource;
use Filament\Resources\Pages\EditRecord;

class EditVisitorPass extends EditRecord
{
    protected static string $resource = VisitorPassResource::class;
}
