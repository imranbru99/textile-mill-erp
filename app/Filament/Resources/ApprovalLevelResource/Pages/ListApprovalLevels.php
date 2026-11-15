<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApprovalLevelResource\Pages;

use App\Filament\Resources\ApprovalLevelResource;
use Filament\Resources\Pages\ListRecords;

class ListApprovalLevels extends ListRecords
{
    protected static string $resource = ApprovalLevelResource::class;
}
