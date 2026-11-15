<?php

declare(strict_types=1);

namespace App\Modules\People\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRecord extends MillModel
{
    protected $table = 'training_records';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'employee_id',
        'topic',
        'trained_on',
        'expires_on',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'trained_on' => 'date',
            'expires_on' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\People\Models\Employee::class, 'employee_id');
    }

}
