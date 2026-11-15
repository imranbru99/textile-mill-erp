<?php

declare(strict_types=1);

namespace App\Modules\Supply;

use App\Support\Modules\ModuleRegistry;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ModuleRegistry::register(__DIR__.'/module.json');
    }
}
