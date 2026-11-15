<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Modules\Master\Models\Machine;
use App\Modules\Spinning\Models\SpinningProduction;
use App\Services\Formulas\RingFrameProductionService;
use App\Services\NumberSeriesService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class FloorEntry extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string|\UnitEnum|null $navigationGroup = 'Spinning';

    protected static ?string $navigationLabel = 'Floor entry';

    protected string $view = 'filament.pages.floor-entry';

    public ?int $machine_id = null;

    public string $rpm = '16500';

    public string $tpi = '22.5';

    public string $count_ne = '30';

    public string $hours = '8';

    public string $efficiency = '92';

    public string $input_kg = '500';

    public static function canAccess(): bool
    {
        return auth()->user()?->permits('spinning.spinningProduction.create') ?? false;
    }

    public function post(RingFrameProductionService $ring, NumberSeriesService $numbers): void
    {
        $machine = Machine::query()->findOrFail($this->machine_id);
        $kg = $ring->kilograms($this->rpm, $this->hours, $this->tpi, $this->count_ne, $this->efficiency, (int) ($machine->spindle_count ?: 1));
        $user = auth()->user();
        $row = SpinningProduction::query()->create([
            'company_id' => $user->company_id,
            'branch_id' => $machine->branch_id,
            'number' => $numbers->next('spinning', (int) $user->company_id, $machine->branch_id),
            'status' => 'posted',
            'document_date' => now()->toDateString(),
            'machine_id' => $machine->id,
            'stage' => 'ring',
            'production_date' => now()->toDateString(),
            'input_kg' => $this->input_kg,
            'output_kg' => $kg,
            'efficiency' => $this->efficiency,
            'spindle_rpm' => $this->rpm,
            'tpi' => $this->tpi,
            'count_ne' => $this->count_ne,
            'hours' => $this->hours,
        ]);
        Notification::make()->title('Posted '.$row->number.' · '.$kg.' kg')->success()->send();
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return ['machines' => Machine::query()->where('section', 'ring')->orderBy('code')->get()];
    }
}
