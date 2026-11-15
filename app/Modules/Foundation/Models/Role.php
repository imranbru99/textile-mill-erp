<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends MillModel
{
    protected $table = 'roles';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'name',
        'slug',
        'is_system',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\RolePermission::class, 'role_id');
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(\App\Modules\Foundation\Models\UserRole::class, 'role_id');
    }

}
