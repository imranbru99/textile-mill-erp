<?php

declare(strict_types=1);

namespace App\Filament\Resources\ApprovalWorkflowResource\Pages;

use App\Filament\Resources\ApprovalWorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalWorkflow extends CreateRecord
{
    protected static string $resource = ApprovalWorkflowResource::class;
}
