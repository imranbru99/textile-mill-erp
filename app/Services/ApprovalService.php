<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Modules\Foundation\Models\ApprovalAction;
use App\Modules\Foundation\Models\ApprovalLevel;
use App\Modules\Foundation\Models\ApprovalRequest;
use App\Modules\Foundation\Models\ApprovalWorkflow;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

final class ApprovalService
{
    public function submit(Model $document, User $user): ApprovalRequest
    {
        $type = class_basename($document);
        $workflow = ApprovalWorkflow::query()
            ->where('company_id', $document->getAttribute('company_id'))
            ->where('document_type', $type)
            ->where('is_active', true)
            ->first();

        $request = ApprovalRequest::query()->create([
            'company_id' => $document->getAttribute('company_id'),
            'branch_id' => $document->getAttribute('branch_id'),
            'document_type' => $type,
            'document_id' => $document->getKey(),
            'number' => $document->getAttribute('number') ?: ('APR-'.$document->getKey()),
            'status' => $workflow ? 'submitted' : 'approved',
            'document_date' => now()->toDateString(),
            'current_level' => 1,
            'requested_by' => $user->id,
            'total_amount' => $document->getAttribute('total_amount') ?? 0,
        ]);

        if (! $workflow) {
            $document->forceFill(['status' => 'approved'])->save();
        } else {
            $document->forceFill(['status' => 'submitted'])->save();
        }

        return $request;
    }

    public function act(ApprovalRequest $request, User $user, string $action, ?string $comment = null): ApprovalRequest
    {
        if (! in_array($action, ['approve', 'reject'], true)) {
            throw new RuntimeException('Unknown approval action.');
        }

        ApprovalAction::query()->create([
            'company_id' => $request->company_id,
            'request_id' => $request->id,
            'level' => $request->current_level,
            'user_id' => $user->id,
            'action' => $action,
            'comment' => $comment,
        ]);

        $workflow = ApprovalWorkflow::query()
            ->where('company_id', $request->company_id)
            ->where('document_type', $request->document_type)
            ->first();
        $levels = $workflow
            ? ApprovalLevel::query()->where('workflow_id', $workflow->id)->orderBy('level')->get()
            : collect();

        if ($action === 'reject') {
            $request->status = 'rejected';
            $request->save();
            $this->touchDocument($request, 'rejected');

            return $request;
        }

        $higher = $levels->first(fn ($level) => (int) $level->level > (int) $request->current_level);
        if ($higher) {
            $request->current_level = $higher->level;
            $request->status = 'submitted';
            $request->save();

            return $request;
        }

        $request->status = 'approved';
        $request->save();
        $this->touchDocument($request, 'approved');

        return $request;
    }

    private function touchDocument(ApprovalRequest $request, string $status): void
    {
        $class = 'App\\Models\\'.$request->document_type;
        if (! class_exists($class)) {
            $class = $this->findModel($request->document_type);
        }
        if (! $class) {
            return;
        }
        $model = $class::query()->find($request->document_id);
        $model?->forceFill(['status' => $status])->save();
    }

    private function findModel(string $basename): ?string
    {
        foreach (glob(app_path('Modules/*/Models/'.$basename.'.php')) ?: [] as $path) {
            $module = basename(dirname($path, 2));

            return 'App\\Modules\\'.$module.'\\Models\\'.$basename;
        }

        return null;
    }
}
