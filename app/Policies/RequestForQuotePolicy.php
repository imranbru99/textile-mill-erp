<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class RequestForQuotePolicy extends MillPolicy
{
    protected string $permission = 'purchase.requestForQuote';
}
