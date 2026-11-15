<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class GoodsReceiptLinePolicy extends MillPolicy
{
    protected string $permission = 'purchase.goodsReceiptLine';
}
