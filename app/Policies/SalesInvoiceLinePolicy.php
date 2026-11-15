<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class SalesInvoiceLinePolicy extends MillPolicy
{
    protected string $permission = 'finance.salesInvoiceLine';
}
