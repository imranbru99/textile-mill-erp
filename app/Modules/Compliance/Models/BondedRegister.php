<?php

declare(strict_types=1);

namespace App\Modules\Compliance\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BondedRegister extends MillModel
{
    protected $table = 'bonded_registers';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'branch_id',
        'item_id',
        'import_qty',
        'consumed_qty',
        'exported_qty',
        'allowed_waste_percent',
        'period',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'import_qty' => 'decimal:4',
            'consumed_qty' => 'decimal:4',
            'exported_qty' => 'decimal:4',
            'allowed_waste_percent' => 'decimal:4',
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Master\Models\Item::class, 'item_id');
    }

}
