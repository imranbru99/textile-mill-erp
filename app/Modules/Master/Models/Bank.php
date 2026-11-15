<?php

declare(strict_types=1);

namespace App\Modules\Master\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends MillModel
{
    protected $table = 'banks';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'name',
        'branch_name',
        'account_no',
        'routing_no',
        'swift',
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

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\BankAccount::class, 'bank_id');
    }

    public function letterOfCredits(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\LetterOfCredit::class, 'bank_id');
    }

    public function exportRealizations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\ExportRealization::class, 'bank_id');
    }

}
