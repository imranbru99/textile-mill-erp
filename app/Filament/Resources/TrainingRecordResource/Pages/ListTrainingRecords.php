<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrainingRecordResource\Pages;

use App\Filament\Resources\TrainingRecordResource;
use Filament\Resources\Pages\ListRecords;

class ListTrainingRecords extends ListRecords
{
    protected static string $resource = TrainingRecordResource::class;
}
