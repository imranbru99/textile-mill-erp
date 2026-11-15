<?php

declare(strict_types=1);

namespace App\Policies;

use App\Policies\MillPolicy;

class GoodsReceiptPolicy extends MillPolicy
{
    protected string $permission = 'purchase.goodsReceipt';
}
