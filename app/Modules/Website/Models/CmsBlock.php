<?php

declare(strict_types=1);

namespace App\Modules\Website\Models;

use App\Models\MillModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsBlock extends MillModel
{
    protected $table = 'cms_blocks';

    protected bool $companyScoped = true;

    /** @var list<string> */
    protected $fillable = [
        'public_id',
        'lock_version',
        'company_id',
        'cms_page_id',
        'key',
        'locale',
        'content',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Foundation\Models\Company::class, 'company_id');
    }

    public function cmsPage(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Website\Models\CmsPage::class, 'cms_page_id');
    }

}
