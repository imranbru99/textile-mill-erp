<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Foundation\Models\NumberSeries;
use Illuminate\Support\Facades\DB;

final class NumberSeriesService
{
    public function next(string $documentType, int $companyId, ?int $branchId = null): string
    {
        return DB::transaction(function () use ($documentType, $companyId, $branchId): string {
            $series = NumberSeries::query()
                ->where('company_id', $companyId)
                ->where('document_type', $documentType)
                ->when($branchId, fn ($q) => $q->where('branch_id', $branchId), fn ($q) => $q->whereNull('branch_id'))
                ->lockForUpdate()
                ->first();

            if (! $series) {
                $series = NumberSeries::query()->create([
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'document_type' => $documentType,
                    'prefix' => strtoupper(substr($documentType, 0, 3)),
                    'next_number' => 1,
                    'padding' => 5,
                    'reset_rule' => 'yearly',
                ]);
            }

            $number = $series->prefix.'-'.now()->format('y').'-'.str_pad((string) $series->next_number, (int) $series->padding, '0', STR_PAD_LEFT);
            $series->next_number = ((int) $series->next_number) + 1;
            $series->save();

            return $number;
        });
    }
}
