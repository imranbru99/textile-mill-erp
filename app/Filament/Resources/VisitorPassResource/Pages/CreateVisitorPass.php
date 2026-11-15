<?php

declare(strict_types=1);

namespace App\Filament\Resources\VisitorPassResource\Pages;

use App\Filament\Resources\VisitorPassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVisitorPass extends CreateRecord
{
    protected static string $resource = VisitorPassResource::class;
}
