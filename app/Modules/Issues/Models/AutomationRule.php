<?php

declare(strict_types=1);

namespace App\Modules\Issues\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends MillModel
{
    protected $table = 'automation_rules';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'module_key',
        'name',
        'trigger',
        'conditions',
        'actions',
        'threshold',
        'is_active',
        'auto_execute',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'actions' => 'array',
            'threshold' => 'decimal:4',
            'is_active' => 'boolean',
            'auto_execute' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function automationLogs(): HasMany
    {
        return $this->hasMany(\App\Modules\Issues\Models\AutomationLog::class, 'automation_rule_id');
    }

}
