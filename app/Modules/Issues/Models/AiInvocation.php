<?php

declare(strict_types=1);

namespace App\Modules\Issues\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiInvocation extends MillModel
{
    protected $table = 'ai_invocations';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'feature',
        'prompt',
        'response',
        'cost_usd',
        'user_id',
        'confirmed',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'cost_usd' => 'decimal:6',
            'confirmed' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
