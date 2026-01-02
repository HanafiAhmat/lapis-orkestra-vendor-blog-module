<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogPostSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $table = $this->table('ampas_blog_posts');
        $table->truncate();
        $table->insert([
            [
                'title'=>'Initial Commit', 
                'slug' => 'initial-commit', 
                'description' => 'Did something', 
                'excerpt' => 'Did something...', 
                'content' => 'Did something lah this is the content', 
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'scheduled_at' => null,
                'comments_enabled' => false,
                'blog_category_id' => 1,
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Hello Lapis Orkestra',
                'slug' => 'hello-lapis-orkestra',
                'description' => 'Introduction to the new PHP framework.',
                'excerpt' => 'Learn what makes Lapis Orkestra special...',
                'content' => '<p>This is a blog post about Lapis Orkestra...</p>',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'scheduled_at' => null,
                'comments_enabled' => true,
                'blog_category_id' => 1,
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title'=>'Just Like A Rising Star', 
                'slug' => 'just-like-a-rising-star', 
                'description' => 'Did another thing', 
                'excerpt' => 'Did another thing for just like another star...',
                'content' => 'Did another thing for just like another star lah this is the content', 
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'scheduled_at' => null,
                'comments_enabled' => true,
                'blog_category_id' => 2,
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ])->saveData();
    }
}
