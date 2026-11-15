<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use Illuminate\Support\Facades\File;

final class PackageHealth
{
    /** @return list<array{name: string, version: string, status: string, note: string}> */
    public function all(): array
    {
        $packages = [
            'imrandevbd/drutogoti' => 'Query and cache telemetry. Enabled with DRUTOGOTI_ENABLED.',
            'imrandevbd/laravel-admin-panel' => 'Portal shell at /desk for buyer and supplier desks.',
            'imrandevbd/laravel-ai-hub' => 'Only AI entry point. Degrades when no provider key is set.',
            'imrandevbd/laravel-ai-catalog' => 'Drafts item copy. A person confirms before it is saved.',
            'imrandevbd/laravel-attendance-hub' => 'Device punches for gate, floor and payroll.',
            'imrandevbd/laravel-unicode-pdf' => 'Bangla and English documents.',
            'imrandevbd/laravel-universal-slug' => 'Public page and product slugs.',
            'imrandevbd/tarikh' => 'Date arithmetic. Bangla calendar is applied in BanglaCalendar because the package build has no calendar provider.',
            'imrandevbd/laravel-filament-master' => 'Installed, but its Filament v3 command trait does not boot on Filament v4, so resources are written for v4.',
            'imrandevbd/laravel-ai-ocr' => 'Manual-first document extraction with an AI fallback.',
        ];

        $installed = [];
        $lock = json_decode((string) File::get(base_path('composer.lock')), true);
        foreach ([...$lock['packages'], ...$lock['packages-dev']] as $package) {
            $installed[$package['name']] = $package['version'];
        }

        $rows = [];
        foreach ($packages as $name => $note) {
            $rows[] = [
                'name' => $name,
                'version' => $installed[$name] ?? 'missing',
                'status' => isset($installed[$name]) ? 'installed' : 'missing',
                'note' => $note,
            ];
        }
        $rows[] = [
            'name' => 'imrandevbd/adhikar',
            'version' => 'not published',
            'status' => 'adapter',
            'note' => 'RBAC is the mill permission table (module.resource.action, branch scoped) behind MillPolicy until Adhikar is on Packagist.',
        ];

        return $rows;
    }
}
