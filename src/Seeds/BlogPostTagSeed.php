<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogPostTagSeed extends AbstractSeed
{
    public function run(): void
    {
        $this->table('ampas_blog_post_tag')->insert([
            [
                'blog_post_id' => 1,
                'blog_tag_id' => 1,
            ],
            [
                'blog_post_id' => 2,
                'blog_tag_id' => 2,
            ],
        ])->saveData();
    }
}
