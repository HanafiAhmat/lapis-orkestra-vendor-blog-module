<?php declare(strict_types=1);

namespace Ampas\Blog\Entities;

use BitSynama\Lapis\Framework\Persistences\AbstractEntity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends AbstractEntity
{
    protected $table = 'ampas_blog_comments';
    protected $primaryKey = 'blog_comment_id';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'blog_post_id', 'blog_post_id');
    }
}
