<?php declare(strict_types=1);

namespace Ampas\Blog;

use Ampas\Blog\Controllers\AdminPostController;
use Ampas\Blog\Controllers\BlogRollController;
use Ampas\Blog\Controllers\PostController;
use BitSynama\Lapis\Framework\Contracts\ModuleRoutesInterface;
use BitSynama\Lapis\Framework\DTO\MiddlewareDefinition;
use BitSynama\Lapis\Framework\Registries\RouteRegistry;
use BitSynama\Lapis\Lapis;
use Psr\Http\Message\ServerRequestInterface;

class BlogRoutes implements ModuleRoutesInterface
{
    public static function register(): void
    {
        $route = Lapis::routeRegistry();
        $route->addGroup(prefix: '/blog', callback: function(RouteRegistry $r) {
            $r->add('GET', '', BlogRollController::class);
            $r->addGroup(prefix: '/posts', callback: function(RouteRegistry $r2) {
                $r2->add('GET', '', [PostController::class, 'list']);
                $r2->add('GET', '/{slug}', [PostController::class, 'show']);
            });
        });

        /** @var string $adminPrefix */
        $adminPrefix = Lapis::configRegistry()->get('app.routes.admin_prefix');
        $route->addGroup(prefix: $adminPrefix . '/blog', callback: function (RouteRegistry $route) {
            $route->addGroup('/posts', function (RouteRegistry $route) {
                $route->add('GET', '', [AdminPostController::class, 'list']);
                $route->add('GET', '/{id:\d+}', [AdminPostController::class, 'show']);
                $route->add('GET', '/create', [AdminPostController::class, 'create']);
                $route->add('GET', '/edit/{id:\d+}', [AdminPostController::class, 'edit']);
                $route->add('POST', '', [AdminPostController::class, 'store']);
                $route->add('PUT', '/{id:\d+}', [AdminPostController::class, 'update']);
                $route->add('DELETE', '/{id:\d+}', [AdminPostController::class, 'destroy']);
            }, [
                new MiddlewareDefinition('core.security.auth')
                // MiddlewareRegistry::get('staffuser', 'role', ['superuser', 'manager'])
            ]);
        });
    }
}
