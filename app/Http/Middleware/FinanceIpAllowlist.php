<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Optional IP gate for finance screens. An empty list allows every address. */
class FinanceIpAllowlist
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('mill.finance_ip_allowlist', []);
        if ($allowed === [] || ! $request->is('admin/sales-invoices*', 'admin/journal-entries*', 'admin/letters-of-credit*')) {
            return $next($request);
        }

        if (! in_array($request->ip(), $allowed, true)) {
            abort(403, 'This terminal is not on the finance allow list.');
        }

        return $next($request);
    }
}
