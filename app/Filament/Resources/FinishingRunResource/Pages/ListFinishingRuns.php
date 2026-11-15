<?php

declare(strict_types=1);

namespace App\Filament\Resources\FinishingRunResource\Pages;

use App\Filament\Resources\FinishingRunResource;
use Filament\Resources\Pages\ListRecords;

class ListFinishingRuns extends ListRecords
{
    protected static string $resource = FinishingRunResource::class;
}
