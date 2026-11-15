<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Modules\Issues\Models\AutomationLog;
use App\Modules\Issues\Models\MillIssue;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class IssueBoard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Issues';

    protected static ?string $navigationLabel = 'Issue board';

    protected string $view = 'filament.pages.issue-board';

    public static function canAccess(): bool
    {
        return auth()->user()?->permits('issues.millIssue.view') ?? false;
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        $issues = MillIssue::query()->latest('id')->limit(80)->get();

        return [
            'columns' => [
                'open' => $issues->where('status', 'open'),
                'approved' => $issues->where('status', 'approved'),
                'closed' => $issues->where('status', 'closed'),
            ],
            'logs' => AutomationLog::query()->latest('id')->limit(20)->get(),
        ];
    }
}
