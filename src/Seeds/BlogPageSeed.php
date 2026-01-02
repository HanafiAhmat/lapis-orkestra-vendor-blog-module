<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogPageSeed extends AbstractSeed
{
    public function run(): void
    {
        $this->table('ampas_blog_pages')->insert([
            [
                'title' => 'About Lapis Orkestra',
                'slug' => 'about-lapis-orkestra',
                'description' => 'Learn more about the Lapis Orkestra framework.',
                'content' => '<p>This page explains the mission of Lapis Orkestra...</p>',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'scheduled_at' => null,
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ])->saveData();
    }
}
