<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class BlogTagSeed extends AbstractSeed
{
    public function run(): void
    {
        $this->table('ampas_blog_tags')->insert([
            [
                'name' => 'PHP',
                'slug' => 'php',
                'created_by_type' => 'staff',
                'created_by_id' => 1,
                'updated_by_type' => 'staff',
                'updated_by_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Slice of Life',
                'slug' => 'slice-of-life',
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
