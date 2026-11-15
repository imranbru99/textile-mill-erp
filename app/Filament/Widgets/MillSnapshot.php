<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Modules\Commercial\Models\SalesOrder;
use App\Modules\Issues\Models\MillIssue;
use App\Modules\Spinning\Models\YarnLot;
use Filament\Widgets\Widget;

class MillSnapshot extends Widget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.mill-snapshot';

    /** @return array<string, int> */
    protected function getViewData(): array
    {
        return [
            'orders' => SalesOrder::query()->count(),
            'yarnLots' => YarnLot::query()->count(),
            'openIssues' => MillIssue::query()->where('status', 'open')->count(),
        ];
    }
}
