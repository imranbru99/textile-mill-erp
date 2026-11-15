<?php

declare(strict_types=1);

namespace App\Filament\Resources\DailySummaryResource\Pages;

use App\Filament\Resources\DailySummaryResource;
use Filament\Resources\Pages\EditRecord;

class EditDailySummary extends EditRecord
{
    protected static string $resource = DailySummaryResource::class;
}
