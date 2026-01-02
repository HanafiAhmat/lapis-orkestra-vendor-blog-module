<?php declare(strict_types=1);

namespace Ampas\Blog\Entities;

use BitSynama\Lapis\Framework\Persistences\AbstractEntity;

class PostTag extends AbstractEntity
{
    protected $table = 'ampas_blog_post_tag';
    protected $primaryKey = 'blog_post_tag_id';
}
