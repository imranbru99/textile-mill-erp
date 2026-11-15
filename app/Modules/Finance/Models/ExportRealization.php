<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExportRealization extends MillModel
{
    protected $table = 'export_realizations';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'sales_invoice_id',
        'bank_id',
        'realized_amount',
        'brc_no',
        'realized_on',
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
            'realized_amount' => 'decimal:4',
            'realized_on' => 'date',
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

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Finance\Models\SalesInvoice::class, 'sales_invoice_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Bank::class, 'bank_id');
    }

}
