<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends MillModel
{
    protected $table = 'journal_entries';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'fiscal_period_id',
        'source_type',
        'source_id',
        'posted_at',
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
            'posted_at' => 'datetime',
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

    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\FiscalPeriod::class, 'fiscal_period_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalLine::class, 'journal_entry_id');
    }

    public function bankStatementLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankStatementLine::class, 'journal_entry_id');
    }

}
