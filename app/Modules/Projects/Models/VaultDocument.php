<?php

declare(strict_types=1);

namespace App\Modules\Projects\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaultDocument extends MillModel
{
    protected $table = 'vault_documents';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'title',
        'category',
        'reference_no',
        'issued_on',
        'expires_on',
        'path',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'expires_on' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

}
