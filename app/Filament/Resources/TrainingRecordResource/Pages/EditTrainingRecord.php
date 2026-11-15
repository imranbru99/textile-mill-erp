<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrainingRecordResource\Pages;

use App\Filament\Resources\TrainingRecordResource;
use Filament\Resources\Pages\EditRecord;

class EditTrainingRecord extends EditRecord
{
    protected static string $resource = TrainingRecordResource::class;
}
