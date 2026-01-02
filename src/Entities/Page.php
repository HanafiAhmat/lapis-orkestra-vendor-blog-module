<?php declare(strict_types=1);

namespace Ampas\Blog\Entities;

use BitSynama\Lapis\Framework\Persistences\AbstractEntity;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Page extends AbstractEntity
{
    protected $table = 'ampas_blog_pages';
    protected $primaryKey = 'blog_page_id';

    public function createdBy(): MorphTo
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    public function updatedBy(): MorphTo
    {
        return $this->morphTo(null, 'updated_by_type', 'updated_by_id');
    }
}
