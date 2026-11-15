<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Modules\Issues\Models\AutomationRule;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AutomationStudio extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Issues';

    protected static ?string $navigationLabel = 'Automation studio';

    protected string $view = 'filament.pages.automation-studio';

    public string $name = '';

    public string $trigger = '';

    public string $threshold = '0';

    public static function canAccess(): bool
    {
        return auth()->user()?->permits('issues.automationRule.create') ?? false;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:160'],
            'trigger' => ['required', 'string', 'max:80'],
            'threshold' => ['required', 'numeric'],
        ]);
        AutomationRule::query()->create([
            'company_id' => auth()->user()->company_id,
            'module_key' => 'system',
            'name' => $this->name,
            'trigger' => $this->trigger,
            'conditions' => ['threshold' => $this->threshold],
            'actions' => ['create_issue' => true, 'notify' => ['owner']],
            'threshold' => $this->threshold,
            'is_active' => true,
        ]);
        $this->reset('name', 'trigger');
        $this->threshold = '0';
        Notification::make()->title('Rule saved')->success()->send();
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return ['rules' => AutomationRule::query()->orderBy('name')->get()];
    }
}
