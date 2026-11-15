<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MillModel;
use App\Models\User;
use App\Support\Modules\ModuleRegistry;

abstract class MillPolicy
{
    protected string $permission;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view');
    }

    public function view(User $user, MillModel $model): bool
    {
        return $this->allows($user, 'view') && $this->sameCompany($user, $model);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create');
    }

    public function update(User $user, MillModel $model): bool
    {
        return $this->allows($user, 'update') && $this->sameCompany($user, $model);
    }

    public function delete(User $user, MillModel $model): bool
    {
        return $this->allows($user, 'delete') && $this->sameCompany($user, $model);
    }

    public function approve(User $user, MillModel $model): bool
    {
        return $this->allows($user, 'approve') && $this->sameCompany($user, $model);
    }

    protected function allows(User $user, string $action): bool
    {
        $module = explode('.', $this->permission)[0] ?? 'foundation';

        return ModuleRegistry::enabled($module) && $user->permits($this->permission.'.'.$action);
    }

    protected function sameCompany(User $user, MillModel $model): bool
    {
        if (! $model->isCompanyScoped() || $user->company_id === null) {
            return true;
        }

        return (int) $model->getAttribute('company_id') === (int) $user->company_id;
    }
}
