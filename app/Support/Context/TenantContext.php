<?php

declare(strict_types=1);

namespace App\Support\Context;

final class TenantContext
{
    private static ?int $companyId = null;

    private static ?int $branchId = null;

    public static function set(?int $companyId, ?int $branchId = null): void
    {
        self::$companyId = $companyId;
        self::$branchId = $branchId;
    }

    public static function companyId(): ?int
    {
        return self::$companyId;
    }

    public static function branchId(): ?int
    {
        return self::$branchId;
    }

    public static function forget(): void
    {
        self::$companyId = null;
        self::$branchId = null;
    }
}
