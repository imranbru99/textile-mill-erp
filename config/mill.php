<?php

return [
    'brand' => env('MILL_BRAND', 'Textile Mill'),
    'locale' => env('APP_LOCALE', 'en'),
    'ai_enabled' => (bool) env('MILL_AI_ENABLED', false),
    'finance_ip_allowlist' => array_values(array_filter(array_map('trim', explode(',', (string) env('MILL_FINANCE_IPS', ''))))),
    'otp_ttl_minutes' => 10,
    'backup_keep' => 7,
];
