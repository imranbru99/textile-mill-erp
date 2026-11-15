<?php

declare(strict_types=1);

namespace App\Modules\Foundation\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalAction extends MillModel
{
    protected $table = 'approval_actions';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'request_id',
        'level',
        'user_id',
        'action',
        'comment',
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

    public function request(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\ApprovalRequest::class, 'request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
