<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMapping extends MillModel
{
    protected $table = 'account_mappings';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'event_key',
        'debit_account_id',
        'credit_account_id',
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

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\Account::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\Account::class, 'credit_account_id');
    }

}
