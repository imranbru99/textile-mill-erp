<?php

declare(strict_types=1);

namespace App\Modules\Website\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends MillModel
{
    protected $table = 'job_postings';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'title',
        'slug',
        'department',
        'location',
        'body',
        'closes_on',
        'is_open',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'closes_on' => 'date',
            'is_open' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\JobApplication::class, 'job_posting_id');
    }

}
