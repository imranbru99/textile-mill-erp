<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterOfCredit extends MillModel
{
    protected $table = 'letters_of_credit';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'lc_type',
        'bank_id',
        'amount',
        'currency_code',
        'expiry_date',
        'margin_percent',
        'number',
        'status',
        'document_date',
        'exchange_rate',
        'notes',
        'total_amount',
        'qr_token',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'expiry_date' => 'date',
            'margin_percent' => 'decimal:4',
            'document_date' => 'date',
            'exchange_rate' => 'decimal:6',
            'total_amount' => 'decimal:4',
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

    public function party(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Party::class, 'party_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Bank::class, 'bank_id');
    }

    public function lcShipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LcShipment::class, 'letter_of_credit_id');
    }

}
