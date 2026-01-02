<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogCategorySeed extends AbstractSeed
{
    public function run(): void
    {
        $this->table('ampas_blog_categories')->insert([
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'sort_order' => 1,
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Random',
                'slug' => 'random',
                'sort_order' => 2,
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
