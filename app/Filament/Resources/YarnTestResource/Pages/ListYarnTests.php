<?php

declare(strict_types=1);

namespace App\Filament\Resources\YarnTestResource\Pages;

use App\Filament\Resources\YarnTestResource;
use Filament\Resources\Pages\ListRecords;

class ListYarnTests extends ListRecords
{
    protected static string $resource = YarnTestResource::class;
}
