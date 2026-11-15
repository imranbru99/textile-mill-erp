<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class InquiryPolicy extends MillPolicy
{
    protected string $permission = 'crm.inquiry';
}
