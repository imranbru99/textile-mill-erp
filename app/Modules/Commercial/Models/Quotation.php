<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends MillModel
{
    protected $table = 'quotations';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'inquiry_id',
        'valid_until',
        'revision',
        'margin_percent',
        'number',
        'status',
        'document_date',
        'currency_code',
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
            'valid_until' => 'date',
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

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Inquiry::class, 'inquiry_id');
    }

    public function quotationLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\QuotationLine::class, 'quotation_id');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\SalesOrder::class, 'quotation_id');
    }

}
