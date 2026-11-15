<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApprovalLevelResource\Pages;

use App\Filament\Resources\ApprovalLevelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalLevel extends CreateRecord
{
    protected static string $resource = ApprovalLevelResource::class;
}
