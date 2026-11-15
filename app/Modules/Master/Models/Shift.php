<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends MillModel
{
    protected $table = 'shifts';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'code',
        'name',
        'starts_at',
        'ends_at',
        'break_minutes',
        'is_night',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_night' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Branch::class, 'branch_id');
    }

    public function spinningProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Spinning\Models\SpinningProduction::class, 'shift_id');
    }

    public function weavingProductions(): HasMany
    {
        return $this->hasMany(\App\Modules\Weaving\Models\WeavingProduction::class, 'shift_id');
    }

    public function attendanceDays(): HasMany
    {
        return $this->hasMany(\App\Modules\People\Models\AttendanceDay::class, 'shift_id');
    }

}
