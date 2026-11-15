<?php

declare(strict_types=1);

namespace App\Modules\Logistics\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transporter extends MillModel
{
    protected $table = 'transporters';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'name',
        'phone',
        'vehicle_no',
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

    public function freightBills(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\FreightBill::class, 'transporter_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(\App\Modules\Logistics\Models\Shipment::class, 'transporter_id');
    }

}
