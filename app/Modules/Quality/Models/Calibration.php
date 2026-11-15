<?php

declare(strict_types=1);

namespace App\Modules\Quality\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calibration extends MillModel
{
    protected $table = 'calibrations';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'instrument',
        'last_done',
        'next_due',
        'certificate_no',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'last_done' => 'date',
            'next_due' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

}
