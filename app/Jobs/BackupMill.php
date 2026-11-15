<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;

class BackupMill implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);
        $stamp = now()->format('Ymd-His');
        $database = config('database.connections.sqlite.database');
        if (is_string($database) && is_file($database)) {
            File::copy($database, $dir.'/mill-'.$stamp.'.sqlite');
        }
        $files = collect(File::files($dir))->sortByDesc(fn ($file) => $file->getMTime())->values();
        $files->slice((int) config('mill.backup_keep', 7))->each(fn ($file) => File::delete($file->getPathname()));
    }
}
