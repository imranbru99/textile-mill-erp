<?php

namespace App\Providers;

use App\Support\Integrations\AiOcrAdapter;
use App\Support\Integrations\Contracts\DocumentReader;
use App\Support\Integrations\Contracts\SmsGateway;
use App\Support\Integrations\LogSmsGateway;
use App\Support\Modules\ModuleRegistry;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsGateway::class, LogSmsGateway::class);
        $this->app->bind(DocumentReader::class, AiOcrAdapter::class);
    }

    public function boot(): void
    {
        ModuleRegistry::loadAll();

        Gate::guessPolicyNamesUsing(function (string $modelClass): string {
            return 'App\\Policies\\'.class_basename($modelClass).'Policy';
        });
    }
}
