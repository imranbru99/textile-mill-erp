<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class LetterOfCreditPolicy extends MillPolicy
{
    protected string $permission = 'finance.letterOfCredit';
}
