<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LcShipment extends MillModel
{
    protected $table = 'lc_shipments';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'letter_of_credit_id',
        'bl_no',
        'shipped_on',
        'amount',
        'acceptance_due',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'shipped_on' => 'date',
            'amount' => 'decimal:4',
            'acceptance_due' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function letterOfCredit(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\LetterOfCredit::class, 'letter_of_credit_id');
    }

}
