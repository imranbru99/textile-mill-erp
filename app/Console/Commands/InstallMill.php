<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\MillSeeder;
use Illuminate\Console\Command;

class InstallMill extends Command
{
    protected $signature = 'mill:install {--fresh : Drop tables and seed the Padma Textile demo}';

    protected $description = 'Migrate the mill and, when asked, load the demo company.';

    public function handle(): int
    {
        $this->call($this->option('fresh') ? 'migrate:fresh' : 'migrate', ['--force' => true]);
        if ($this->option('fresh') || \App\Modules\Foundation\Models\Company::query()->doesntExist()) {
            $this->call('db:seed', ['--class' => MillSeeder::class, '--force' => true]);
            $this->info('Login admin@padma-textile.test / password at /admin');
            $this->info('Buyer desk buyer@padma-textile.test / password at /desk/login');
        }

        return self::SUCCESS;
    }
}
