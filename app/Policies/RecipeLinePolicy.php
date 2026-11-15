<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class RecipeLinePolicy extends MillPolicy
{
    protected string $permission = 'dyeing.recipeLine';
}
