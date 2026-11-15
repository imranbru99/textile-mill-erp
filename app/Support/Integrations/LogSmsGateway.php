<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Support\Integrations\Contracts\SmsGateway;
use Illuminate\Support\Facades\Log;

/** Pluggable Bangladesh SMS adapter. Logs until a gateway key is configured. */
final class LogSmsGateway implements SmsGateway
{
    public function send(string $to, string $message): void
    {
        Log::info('sms.outbound', ['to' => $to, 'message' => $message]);
    }
}
