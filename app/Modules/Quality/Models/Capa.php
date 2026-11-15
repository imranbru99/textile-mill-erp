<?php

declare(strict_types=1);

namespace App\Modules\Quality\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Capa extends MillModel
{
    protected $table = 'capas';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'ncr_id',
        'action',
        'owner_id',
        'due_date',
        'verified_at',
        'status',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'verified_at' => 'datetime',
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

    public function ncr(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Quality\Models\Ncr::class, 'ncr_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

}
