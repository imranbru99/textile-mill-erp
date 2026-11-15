<?php

declare(strict_types=1);

namespace App\Modules\Website\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends MillModel
{
    protected $table = 'contact_messages';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'name',
        'email',
        'phone',
        'message',
        'inquiry_id',
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

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Commercial\Models\Inquiry::class, 'inquiry_id');
    }

}
