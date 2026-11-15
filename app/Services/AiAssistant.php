<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Issues\Models\AiInvocation;
use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

/** Every AI call goes through laravel-ai-hub and is stored for a person to confirm. */
final class AiAssistant
{
    public function advise(int $companyId, string $feature, string $prompt, ?int $userId = null): ?string
    {
        $row = AiInvocation::query()->create([
            'company_id' => $companyId,
            'feature' => $feature,
            'prompt' => $prompt,
            'user_id' => $userId,
            'confirmed' => false,
        ]);

        if (! config('mill.ai_enabled')) {
            $row->response = 'AI is switched off. Use the rule result.';
            $row->save();

            return null;
        }

        try {
            $response = AIHub::prompt($prompt)->forJob($feature)->generate();
            $row->response = (string) $response->content;
            $row->cost_usd = (string) ($response->costUsd ?? '0');
            $row->save();

            return $row->response;
        } catch (Throwable $e) {
            $row->response = 'AI unavailable: '.$e->getMessage();
            $row->save();

            return null;
        }
    }
}
