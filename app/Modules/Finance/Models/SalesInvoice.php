<?php

declare(strict_types=1);

namespace App\Modules\Finance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesInvoice extends MillModel
{
    protected $table = 'sales_invoices';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'sales_order_id',
        'shipment_id',
        'due_date',
        'vat_amount',
        'grand_total',
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
            'due_date' => 'date',
            'vat_amount' => 'decimal:4',
            'grand_total' => 'decimal:4',
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

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\SalesOrder::class, 'sales_order_id');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Logistics\Models\Shipment::class, 'shipment_id');
    }

    public function salesInvoiceLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\SalesInvoiceLine::class, 'sales_invoice_id');
    }

    public function exportRealizations(): HasMany
    {
        return $this->hasMany(\App\Modules\Finance\Models\ExportRealization::class, 'sales_invoice_id');
    }

}
