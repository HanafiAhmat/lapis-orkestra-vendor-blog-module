<?php declare(strict_types=1);

namespace Ampas\Blog\Entities;

use BitSynama\Lapis\Framework\Persistences\AbstractEntity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends AbstractEntity
{
    protected $table = 'ampas_blog_tags';
    protected $primaryKey = 'blog_tag_id';

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'ampas_blog_post_tag',
            'blog_tag_id',
            'blog_post_id'
        );
    }

    public function createdBy(): MorphTo
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    public function updatedBy(): MorphTo
    {
        return $this->morphTo(null, 'updated_by_type', 'updated_by_id');
    }
}
