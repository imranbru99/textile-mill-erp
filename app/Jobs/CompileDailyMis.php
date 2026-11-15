<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Modules\Foundation\Models\Branch;
use App\Modules\Reports\Models\DailySummary;
use App\Modules\Spinning\Models\SpinningProduction;
use App\Services\Formulas\RingFrameProductionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CompileDailyMis implements ShouldQueue
{
    use Queueable;

    public function handle(RingFrameProductionService $ring): void
    {
        foreach (Branch::query()->get() as $branch) {
            $rows = SpinningProduction::query()
                ->where('branch_id', $branch->id)
                ->whereDate('production_date', now()->toDateString())
                ->get();
            $output = '0';
            $waste = '0';
            $input = '0';
            foreach ($rows as $row) {
                $output = \App\Support\Decimal::add($output, (string) $row->output_kg, 4);
                $waste = \App\Support\Decimal::add($waste, (string) $row->waste_kg, 4);
                $input = \App\Support\Decimal::add($input, (string) $row->input_kg, 4);
            }
            DailySummary::query()->updateOrCreate(
                [
                    'company_id' => $branch->company_id,
                    'branch_id' => $branch->id,
                    'summary_date' => now()->toDateString(),
                    'section' => 'spinning',
                ],
                [
                    'output_qty' => $output,
                    'waste_percent' => \App\Support\Decimal::cmp($input, '0', 4) === 0 ? '0' : $ring->wastePercent($input, $output),
                    'payload' => ['input_kg' => $input, 'waste_kg' => $waste],
                ],
            );
        }
    }
}
