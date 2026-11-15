<?php

declare(strict_types=1);

namespace App\Filament\Resources\DailySummaryResource\Pages;

use App\Filament\Resources\DailySummaryResource;
use Filament\Resources\Pages\ListRecords;

class ListDailySummarys extends ListRecords
{
    protected static string $resource = DailySummaryResource::class;
}
