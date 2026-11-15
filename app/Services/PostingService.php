<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Finance\Models\Account;
use App\Modules\Finance\Models\AccountMapping;
use App\Modules\Finance\Models\JournalEntry;
use App\Modules\Finance\Models\JournalLine;
use App\Support\Decimal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class PostingService
{
    public function __construct(private readonly NumberSeriesService $numbers) {}

    /**
     * @param  list<array{account: string, debit?: string, credit?: string, narration?: string}>  $lines
     */
    public function post(int $companyId, ?int $branchId, string $narration, array $lines, ?Model $source = null): JournalEntry
    {
        $debit = '0.0000';
        $credit = '0.0000';
        foreach ($lines as $line) {
            $debit = Decimal::add($debit, $line['debit'] ?? '0', 4);
            $credit = Decimal::add($credit, $line['credit'] ?? '0', 4);
        }
        if (Decimal::cmp($debit, $credit, 4) !== 0 || Decimal::cmp($debit, '0', 4) === 0) {
            throw new RuntimeException('Journal is not balanced.');
        }

        return DB::transaction(function () use ($companyId, $branchId, $narration, $lines, $source, $debit): JournalEntry {
            $entry = JournalEntry::query()->create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'number' => $this->numbers->next('journal', $companyId, $branchId),
                'status' => 'posted',
                'document_date' => now()->toDateString(),
                'notes' => $narration,
                'total_amount' => $debit,
                'posted_at' => now(),
                'source_type' => $source ? $source::class : null,
                'source_id' => $source?->getKey(),
            ]);

            foreach ($lines as $line) {
                $account = Account::query()->where('company_id', $companyId)->where('code', $line['account'])->first();
                if (! $account) {
                    throw new RuntimeException('Missing account '.$line['account']);
                }
                JournalLine::query()->create([
                    'company_id' => $companyId,
                    'journal_entry_id' => $entry->id,
                    'account_id' => $account->id,
                    'debit' => Decimal::of($line['debit'] ?? '0', 4),
                    'credit' => Decimal::of($line['credit'] ?? '0', 4),
                    'narration' => $line['narration'] ?? $narration,
                ]);
            }

            return $entry->load('journalLines');
        });
    }

    public function postEvent(int $companyId, ?int $branchId, string $event, string $amount, string $narration, ?Model $source = null): JournalEntry
    {
        $mapping = AccountMapping::query()->where('company_id', $companyId)->where('event_key', $event)->first();
        if (! $mapping) {
            throw new RuntimeException('No account mapping for '.$event);
        }
        $debit = Account::query()->findOrFail($mapping->debit_account_id);
        $credit = Account::query()->findOrFail($mapping->credit_account_id);

        return $this->post($companyId, $branchId, $narration, [
            ['account' => $debit->code, 'debit' => $amount, 'narration' => $narration],
            ['account' => $credit->code, 'credit' => $amount, 'narration' => $narration],
        ], $source);
    }
}
