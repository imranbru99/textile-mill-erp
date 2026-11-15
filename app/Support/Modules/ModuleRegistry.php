<?php

declare(strict_types=1);

namespace App\Support\Modules;

use App\Modules\Foundation\Models\CompanyModule;
use App\Support\Context\TenantContext;
use Illuminate\Support\Facades\Schema;

final class ModuleRegistry
{
    /** @var array<string, array<string, mixed>> */
    private static array $modules = [];

    public static function register(string $jsonPath): void
    {
        if (! is_file($jsonPath)) {
            return;
        }

        /** @var array<string, mixed> $data */
        $data = json_decode((string) file_get_contents($jsonPath), true, 512, JSON_THROW_ON_ERROR);
        $key = (string) $data['key'];
        self::$modules[$key] = $data;
    }

    public static function loadAll(): void
    {
        foreach (glob(app_path('Modules/*/module.json')) ?: [] as $path) {
            self::register($path);
        }
    }

    /** @return array<string, array<string, mixed>> */
    public static function all(): array
    {
        if (self::$modules === []) {
            self::loadAll();
        }

        return self::$modules;
    }

    public static function enabled(?string $key): bool
    {
        if ($key === null || $key === 'foundation' || $key === 'master') {
            return true;
        }

        $modules = self::all();
        if (! isset($modules[$key])) {
            return false;
        }

        $companyId = TenantContext::companyId() ?? auth()->user()?->company_id;
        if (! $companyId || ! Schema::hasTable('company_modules')) {
            return true;
        }

        $row = CompanyModule::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('module_key', $key)
            ->first();

        if ($row && ! $row->enabled) {
            return false;
        }

        foreach ($modules[$key]['dependencies'] ?? [] as $dependency) {
            if (! self::enabled((string) $dependency)) {
                return false;
            }
        }

        return true;
    }

    /** @return list<string> */
    public static function permissions(): array
    {
        $permissions = [];
        foreach (self::all() as $module) {
            foreach ($module['permissions'] ?? [] as $permission) {
                $permissions[] = (string) $permission;
            }
        }

        return $permissions;
    }
}
