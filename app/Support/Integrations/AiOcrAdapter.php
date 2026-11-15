<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Support\Integrations\Contracts\DocumentReader;
use ImranDevBd\AiOcr\Facades\AiOcr;
use Throwable;

final class AiOcrAdapter implements DocumentReader
{
    public function read(string $path, string $kind): array
    {
        try {
            $result = AiOcr::file($path)->as($kind)->manualOnly()->extract();

            return [
                'text' => method_exists($result, 'text') ? (string) $result->text() : (string) $result,
                'confirmed' => false,
            ];
        } catch (Throwable $e) {
            return ['text' => '', 'confirmed' => false, 'error' => $e->getMessage()];
        }
    }
}
