<?php

declare(strict_types=1);

namespace App\Modules\Dyeing\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends MillModel
{
    protected $table = 'recipes';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'code',
        'name',
        'version',
        'liquor_ratio',
        'dye_class',
        'locked',
        'parent_id',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'liquor_ratio' => 'decimal:4',
            'locked' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function labDips(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\LabDip::class, 'recipe_id');
    }

    public function recipeLines(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\RecipeLine::class, 'recipe_id');
    }

    public function dyeBatchs(): HasMany
    {
        return $this->hasMany(\App\Modules\Dyeing\Models\DyeBatch::class, 'recipe_id');
    }

}
