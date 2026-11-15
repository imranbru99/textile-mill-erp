<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Issues\Models\AutomationLog;
use App\Modules\Issues\Models\AutomationRule;
use App\Modules\Issues\Models\MillIssue;
use App\Support\Decimal;
use Illuminate\Database\Eloquent\Model;

final class IssueEngine
{
    public function __construct(private readonly NumberSeriesService $numbers) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function evaluate(string $trigger, int $companyId, ?int $branchId, array $context, ?Model $source = null): ?MillIssue
    {
        $rules = AutomationRule::query()
            ->where('company_id', $companyId)
            ->where('trigger', $trigger)
            ->where('is_active', true)
            ->get();

        $created = null;
        foreach ($rules as $rule) {
            if (! $this->matches($rule, $context)) {
                continue;
            }
            $created = $this->open($rule, $companyId, $branchId, $context, $source);
        }

        return $created;
    }

    /** @param array<string, mixed> $context */
    private function matches(AutomationRule $rule, array $context): bool
    {
        $actual = (string) ($context['value'] ?? '0');
        $threshold = (string) ($rule->threshold ?? '0');
        $operator = (string) ($context['operator'] ?? 'gt');

        return match ($operator) {
            'gte' => Decimal::cmp($actual, $threshold, 4) >= 0,
            'lt' => Decimal::cmp($actual, $threshold, 4) === -1,
            'eq' => Decimal::cmp($actual, $threshold, 4) === 0,
            'always' => true,
            default => Decimal::cmp($actual, $threshold, 4) === 1,
        };
    }

    /** @param array<string, mixed> $context */
    private function open(AutomationRule $rule, int $companyId, ?int $branchId, array $context, ?Model $source): MillIssue
    {
        $issue = MillIssue::query()->create([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'number' => $this->numbers->next('issue', $companyId, $branchId),
            'status' => 'open',
            'document_date' => now()->toDateString(),
            'issue_type' => $rule->trigger,
            'severity' => (string) ($context['severity'] ?? 'medium'),
            'source_type' => $source ? $source::class : null,
            'source_id' => $source?->getKey(),
            'sla_hours' => 24,
            'due_at' => now()->addHours(24),
            'recommendation' => $rule->name.': '.(string) ($context['message'] ?? ''),
            'notes' => (string) ($context['message'] ?? $rule->name),
        ]);

        AutomationLog::query()->create([
            'company_id' => $companyId,
            'automation_rule_id' => $rule->id,
            'mill_issue_id' => $issue->id,
            'action' => $rule->auto_execute ? 'auto_executed' : 'issue_opened',
            'payload' => ['context' => $context, 'undo' => $context['undo'] ?? null],
        ]);

        return $issue;
    }
}
