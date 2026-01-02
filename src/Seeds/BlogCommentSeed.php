<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogCommentSeed extends AbstractSeed
{
    public function run(): void
    {
        $this->table('ampas_blog_comments')->insert([
            [
                'blog_post_id' => 1,
                'author_type' => 'staff',
                'author_id' => 1,
                'author_name' => 'Admin User',
                'author_email' => 'admin@example.com',
                'content' => 'This is the first comment on Lapis Orkestra.',
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ])->saveData();
    }
}
