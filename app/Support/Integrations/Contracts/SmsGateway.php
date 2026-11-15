<?php

declare(strict_types=1);

namespace App\Support\Integrations\Contracts;

interface SmsGateway
{
    public function send(string $to, string $message): void;
}
