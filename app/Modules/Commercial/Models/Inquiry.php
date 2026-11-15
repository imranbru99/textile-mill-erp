<?php

declare(strict_types=1);

namespace App\Modules\Commercial\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends MillModel
{
    protected $table = 'inquiries';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'party_id',
        'subject',
        'source',
        'contact_name',
        'email',
        'phone',
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

    public function quotations(): HasMany
    {
        return $this->hasMany(\App\Modules\Commercial\Models\Quotation::class, 'inquiry_id');
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\ContactMessage::class, 'inquiry_id');
    }

}
