<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'branch_id',
        'phone',
        'locale',
        'user_type',
        'is_active',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /** @var list<string>|null */
    private ?array $permissionCache = null;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active
            && ! in_array($this->user_type, ['buyer', 'supplier'], true);
    }

    public function permits(string $ability): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return in_array($ability, $this->permissionNames(), true);
    }

    public function hasRole(string $slug): bool
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('user_role')) {
            return false;
        }

        return DB::table('user_role')
            ->join('roles', 'roles.id', '=', 'user_role.role_id')
            ->where('user_role.user_id', $this->id)
            ->where('roles.slug', $slug)
            ->whereNull('roles.deleted_at')
            ->exists();
    }

    /** @return list<string> */
    public function permissionNames(): array
    {
        if ($this->permissionCache !== null) {
            return $this->permissionCache;
        }

        if (! Schema::hasTable('permissions')) {
            return $this->permissionCache = [];
        }

        $names = DB::table('permissions')
            ->join('role_permission', 'role_permission.permission_id', '=', 'permissions.id')
            ->join('user_role', 'user_role.role_id', '=', 'role_permission.role_id')
            ->where('user_role.user_id', $this->id)
            ->pluck('permissions.name')
            ->all();

        return $this->permissionCache = array_values(array_unique($names));
    }
}
