<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Commercial\Models\SalesOrder;
use App\Modules\Inventory\Models\StockBalance;
use App\Modules\Issues\Models\MillIssue;
use App\Modules\Master\Models\Machine;
use App\Modules\Spinning\Models\SpinningProduction;
use App\Services\Formulas\RingFrameProductionService;
use App\Services\NumberSeriesService;
use App\Support\Context\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MillController extends Controller
{
    public function orders(): JsonResponse
    {
        $this->tenant();

        return response()->json(SalesOrder::query()->latest('id')->limit(50)->get(['id', 'number', 'status', 'delivery_date', 'total_amount', 'currency_code']));
    }

    public function stock(): JsonResponse
    {
        $this->tenant();

        return response()->json(StockBalance::query()->with('item:id,code,name')->limit(100)->get());
    }

    public function issues(): JsonResponse
    {
        $this->tenant();

        return response()->json(MillIssue::query()->latest('id')->limit(50)->get(['id', 'number', 'status', 'issue_type', 'severity', 'recommendation', 'due_at']));
    }

    public function floor(Request $request, RingFrameProductionService $ring, NumberSeriesService $numbers): JsonResponse
    {
        $this->tenant();
        $data = $request->validate([
            'machine_id' => ['required', 'integer'],
            'rpm' => ['required', 'numeric'],
            'tpi' => ['required', 'numeric'],
            'count_ne' => ['required', 'numeric'],
            'hours' => ['required', 'numeric'],
            'efficiency' => ['required', 'numeric'],
            'input_kg' => ['required', 'numeric'],
        ]);
        $machine = Machine::query()->findOrFail($data['machine_id']);
        $kg = $ring->kilograms((string) $data['rpm'], (string) $data['hours'], (string) $data['tpi'], (string) $data['count_ne'], (string) $data['efficiency'], (int) ($machine->spindle_count ?: 1));
        $user = $request->user();
        $row = SpinningProduction::query()->create([
            'company_id' => $user->company_id,
            'branch_id' => $machine->branch_id,
            'number' => $numbers->next('spinning', (int) $user->company_id, $machine->branch_id),
            'status' => 'posted',
            'document_date' => now()->toDateString(),
            'machine_id' => $machine->id,
            'stage' => 'ring',
            'production_date' => now()->toDateString(),
            'input_kg' => $data['input_kg'],
            'output_kg' => $kg,
            'efficiency' => $data['efficiency'],
            'spindle_rpm' => $data['rpm'],
            'tpi' => $data['tpi'],
            'count_ne' => $data['count_ne'],
            'hours' => $data['hours'],
        ]);

        return response()->json(['number' => $row->number, 'output_kg' => $kg], 201);
    }

    private function tenant(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->company_id, 403);
        TenantContext::set((int) $user->company_id, $user->branch_id ? (int) $user->branch_id : null);
    }
}
