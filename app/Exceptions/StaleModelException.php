<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class StaleModelException extends RuntimeException
{
    public function __construct(public readonly Model $model)
    {
        parent::__construct('This record was updated by someone else. Reload and try again.');
    }
}
