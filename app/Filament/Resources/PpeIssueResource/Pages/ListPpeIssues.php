<?php

declare(strict_types=1);

namespace App\Filament\Resources\PpeIssueResource\Pages;

use App\Filament\Resources\PpeIssueResource;
use Filament\Resources\Pages\ListRecords;

class ListPpeIssues extends ListRecords
{
    protected static string $resource = PpeIssueResource::class;
}
