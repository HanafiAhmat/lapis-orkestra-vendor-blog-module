<?php declare(strict_types=1);

namespace Ampas\Blog\Entities;

use BitSynama\Lapis\Framework\Persistences\AbstractEntity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Post extends AbstractEntity
{
    protected $table = 'ampas_blog_posts';
    protected $primaryKey = 'blog_post_id';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'blog_category_id', 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'ampas_blog_post_tag',
            'blog_post_id',
            'blog_tag_id'
        );
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_post_id', 'blog_post_id');
    }

    public function createdBy(): MorphTo
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }

    public function updatedBy(): MorphTo
    {
        return $this->morphTo(null, 'updated_by_type', 'updated_by_id');
    }

    protected function tagIds(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tags->count() > 0 ? $this->tags->pluck('blog_tag_id')->toArray() : []
        );
    }
}
