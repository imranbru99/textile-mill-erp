<?php

declare(strict_types=1);

namespace App\Support\Integrations\Contracts;

interface DocumentReader
{
    /** @return array<string, mixed> */
    public function read(string $path, string $kind): array;
}
