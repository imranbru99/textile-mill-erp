<?php

declare(strict_types=1);

namespace App\Modules\Website\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends MillModel
{
    protected $table = 'job_applications';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'job_posting_id',
        'applicant_name',
        'email',
        'phone',
        'status',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [

        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Website\Models\JobPosting::class, 'job_posting_id');
    }

}
