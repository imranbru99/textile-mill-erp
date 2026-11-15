<?php

declare(strict_types=1);

namespace App\Modules\Website\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends MillModel
{
    protected $table = 'cms_pages';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'title',
        'slug',
        'locale',
        'excerpt',
        'body',
        'seo_title',
        'seo_description',
        'published',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function cmsBlocks(): HasMany
    {
        return $this->hasMany(\App\Modules\Website\Models\CmsBlock::class, 'cms_page_id');
    }

}
