<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends MillModel
{
    protected $table = 'permissions';

    protected bool $companyScoped = false;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'name',
        'module',
        'resource',
        'action',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [

        ];
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\RolePermission::class, 'permission_id');
    }

}
