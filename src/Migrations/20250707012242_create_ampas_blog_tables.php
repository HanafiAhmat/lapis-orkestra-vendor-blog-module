<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAmpasBlogTables extends AbstractMigration
{
    public function change(): void
    {
        // ampas_blog_categories
        $this->table('ampas_blog_categories', [
                'id' => 'blog_category_id',
                'comment' => 'Ampas Blog Module Categories'
            ])
            ->addColumn('name', 'string', ['limit' => 100, 'comment' => 'Blog Category Name'])
            ->addColumn('slug', 'string', ['limit' => 120, 'comment' => 'Blog Category pretty url'])
            ->addColumn('sort_order', 'integer', ['null' => true, 'comment' => 'Blog Category optional ordering'])
            ->addColumn('created_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that created the category'])
            ->addColumn('created_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that created the category'])
            ->addColumn('updated_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that updated the category'])
            ->addColumn('updated_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that updated the category'])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        // ampas_blog_tags
        $this->table('ampas_blog_tags', [
                'id' => 'blog_tag_id',
                'comment' => 'Ampas Blog Module Tags'
            ])
            ->addColumn('name', 'string', ['limit' => 100, 'comment' => 'Blog Tag Name'])
            ->addColumn('slug', 'string', ['limit' => 120, 'comment' => 'Blog Tag pretty url'])
            ->addColumn('created_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that created the tag'])
            ->addColumn('created_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that created the tag'])
            ->addColumn('updated_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that updated the tag'])
            ->addColumn('updated_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that updated the tag'])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        // blog_posts
        $this->table('ampas_blog_posts', [
                'id' => 'blog_post_id',
                'comment' => 'Ampas Blog Module Posts'
            ])
            ->addColumn('title', 'string', ['limit' => 196, 'comment' => 'Blog Tag Title'])
            ->addColumn('slug', 'string', ['limit' => 254, 'comment' => 'Blog Tag pretty url'])
            ->addColumn('description', 'string', ['limit' => 154, 'null' => true, 'comment' => 'Short summary'])
            ->addColumn('excerpt', 'text', ['null' => true, 'comment' => 'For previews'])
            ->addColumn('content', 'text', ['comment' => 'Markdown/HTML body'])
            ->addColumn('status', 'string', ['limit' => 30, 'comment' => 'Post status'])
            ->addColumn('published_at', 'datetime', ['null' => true, 'comment' => 'Timestamp of post publishing'])
            ->addColumn('scheduled_at', 'datetime', ['null' => true, 'comment' => 'Timestamp of post scheduling'])
            ->addColumn('comments_enabled', 'boolean', ['default' => true, 'comment' => 'Flag to allow comments'])
            ->addColumn('blog_category_id', 'integer', ['signed' => false, 'null' => true, 'comment' => 'Blog Category assigned to the post'])
            ->addColumn('created_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that created the post'])
            ->addColumn('created_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that created the post'])
            ->addColumn('updated_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that updated the post'])
            ->addColumn('updated_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that updated the post'])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        // blog_post_tag
        $this->table('ampas_blog_post_tag', [
                'id' => 'blog_post_tag_id',
                'comment' => 'Ampas Blog Module Post and tag Pivot table'
            ])
            ->addColumn('blog_post_id', 'integer', ['signed' => false, 'comment' => 'Related Blog Post ID'])
            ->addColumn('blog_tag_id', 'integer', ['signed' => false, 'comment' => 'Related Blog Tag ID'])
            ->addIndex(['blog_post_id', 'blog_tag_id'], ['unique' => true])
            ->create();

        // blog_comments
        $this->table('ampas_blog_comments', [
                'id' => 'blog_comment_id',
                'comment' => 'Ampas Blog Module Comments'
            ])
            ->addColumn('blog_post_id', 'integer', ['signed' => false, 'comment' => 'Related Blog Post ID'])
            ->addColumn('author_type', 'string', ['limit' => 50, 'null' => true, 'comment' => 'User Type that created the comment'])
            ->addColumn('author_id', 'biginteger', ['signed' => false, 'null' => true, 'comment' => 'User ID that created the comment'])
            ->addColumn('author_name', 'string', ['limit' => 254, 'null' => true, 'comment' => 'Name of User that created the comment'])
            ->addColumn('author_email', 'string', ['limit' => 254, 'null' => true, 'comment' => 'Email of User that created the comment'])
            ->addColumn('content', 'text', ['comment' => 'Contents of the comment'])
            ->addColumn('status', 'string', ['limit' => 30, 'comment' => 'Comment status'])
            ->addTimestamps()
            ->create();

        // blog_pages
        $this->table('ampas_blog_pages', [
                'id' => 'blog_page_id',
                'comment' => 'Ampas Blog Module Pages'
            ])
            ->addColumn('title', 'string', ['limit' => 196, 'comment' => 'Blog Page Title'])
            ->addColumn('slug', 'string', ['limit' => 254, 'comment' => 'Blog Page pretty url'])
            ->addColumn('description', 'string', ['limit' => 154, 'null' => true, 'comment' => 'Short summary'])
            ->addColumn('content', 'text', ['comment' => 'Markdown/HTML body'])
            ->addColumn('status', 'string', ['limit' => 30, 'comment' => 'Page status'])
            ->addColumn('published_at', 'datetime', ['null' => true, 'comment' => 'Timestamp of page publishing'])
            ->addColumn('scheduled_at', 'datetime', ['null' => true, 'comment' => 'Timestamp of page scheduling'])
            ->addColumn('created_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that created the page'])
            ->addColumn('created_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that created the page'])
            ->addColumn('updated_by_type', 'string', ['limit' => 50, 'comment' => 'User Type that updated the page'])
            ->addColumn('updated_by_id', 'integer', ['signed' => false, 'comment' => 'User ID that updated the page'])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();
    }
}
