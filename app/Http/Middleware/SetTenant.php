<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Context\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->company_id) {
            TenantContext::set((int) $user->company_id, $user->branch_id ? (int) $user->branch_id : null);
        }

        return $next($request);
    }
}
