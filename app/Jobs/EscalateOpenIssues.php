<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Modules\Issues\Models\MillIssue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EscalateOpenIssues implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        MillIssue::query()
            ->where('status', 'open')
            ->where('due_at', '<', now())
            ->where('severity', '!=', 'critical')
            ->each(function (MillIssue $issue): void {
                $issue->severity = $issue->severity === 'high' ? 'critical' : 'high';
                $issue->notes = trim((string) $issue->notes.' Escalated at '.now()->toDateTimeString());
                $issue->save();
            });
    }
}
