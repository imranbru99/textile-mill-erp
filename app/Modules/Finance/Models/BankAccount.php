<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends MillModel
{
    protected $table = 'bank_accounts';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'bank_id',
        'name',
        'account_no',
        'currency_code',
        'gl_account_id',
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

    public function bank(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Bank::class, 'bank_id');
    }

    public function glAccount(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\Account::class, 'gl_account_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Receipt::class, 'bank_account_id');
    }

    public function supplierPayments(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SupplierPayment::class, 'bank_account_id');
    }

    public function bankStatementLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankStatementLine::class, 'bank_account_id');
    }

    public function cheques(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\Cheque::class, 'bank_account_id');
    }

}
