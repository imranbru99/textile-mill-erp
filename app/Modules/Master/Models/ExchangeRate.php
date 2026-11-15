<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends MillModel
{
    protected $table = 'exchange_rates';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'currency_code',
        'rate_date',
        'rate',
        'source',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'rate_date' => 'date',
            'rate' => 'decimal:6',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

}
