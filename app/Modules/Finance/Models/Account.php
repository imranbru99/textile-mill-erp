<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends MillModel
{
    protected $table = 'accounts';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'account_type',
        'parent_id',
        'is_postable',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'is_postable' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\JournalLine::class, 'account_id');
    }

    public function debitAccountAccountmappings(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\AccountMapping::class, 'debit_account_id');
    }

    public function creditAccountAccountmappings(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\AccountMapping::class, 'credit_account_id');
    }

    public function glAccountBankaccounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankAccount::class, 'gl_account_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Budget::class, 'account_id');
    }

}
