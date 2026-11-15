<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankStatementLine extends MillModel
{
    protected $table = 'bank_statement_lines';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'bank_account_id',
        'txn_date',
        'description',
        'amount',
        'matched',
        'journal_entry_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'txn_date' => 'date',
            'amount' => 'decimal:4',
            'matched' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\BankAccount::class, 'bank_account_id');
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\JournalEntry::class, 'journal_entry_id');
    }

}
