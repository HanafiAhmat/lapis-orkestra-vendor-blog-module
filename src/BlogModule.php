<?php declare(strict_types=1);

namespace Ampas\Blog;

use Ampas\Blog\Interactors\BlogInteractor;
use BitSynama\Lapis\Framework\Contracts\ModuleInterface;
use BitSynama\Lapis\Framework\DTO\MenuItemDefinition;
use BitSynama\Lapis\Lapis;

class BlogModule implements ModuleInterface
{
    public static function registerHandlers(): void
    {
        Lapis::interactorRegistry()->set(
            'vendor.ampas.blog',
            BlogInteractor::class
        );
    }

    public static function registerRoutes(): void
    {
        BlogRoutes::register();
    }

    public static function registerUIs(): void
    {
        $adminPrefix = Lapis::configRegistry()->get('app.routes.admin_prefix');

        Lapis::adminMenuRegistry()->set('main', MenuItemDefinition::fromArray([
            'id'    => 'blog',
            'label' => 'Blog',
            'order' => 10,
            'children' => [
                [
                    'id'    => 'posts',
                    'label' => 'Posts',
                    'icon'  => 'bi-stickies',
                    'href'  => $adminPrefix . '/blog/posts',
                    'order' => 10,
                ],
                [
                    'id'    => 'tags',
                    'label' => 'Tags',
                    'icon'  => 'bi-tags',
                    'href'  => $adminPrefix . '/blog/tags',
                    'order' => 20,
                ],
            ]
        ]));

        Lapis::publicMenuRegistry()->set('main', MenuItemDefinition::fromArray([
            'id'    => 'blog',
            'label' => 'Blog',
            'order' => 10,
            'children' => [
                [
                    'id'    => 'tags',
                    'label' => 'Tags',
                    'href'  => '/blog/tags',
                    'order' => 10,
                ],
            ]
        ]));

        Lapis::publicMenuRegistry()->set('main', MenuItemDefinition::fromArray([
            'id'    => 'posts',
            'label' => 'Posts',
            'href'  => '/blog/posts',
            'order' => 11,
        ]));
    }
}
