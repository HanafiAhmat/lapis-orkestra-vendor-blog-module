<?php declare(strict_types=1);

namespace Ampas\Blog\Controllers;

use Ampas\Blog\Entities\Post;
use BitSynama\Lapis\Framework\Controllers\AbstractController;
use BitSynama\Lapis\Framework\DTO\ActionResponse;
use BitSynama\Lapis\Lapis;
use Psr\Http\Message\ServerRequestInterface;

final class PostController extends AbstractController
{
    public function list(ServerRequestInterface $request):  ActionResponse
    {
        $posts = new Post();
        $posts = $posts->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data:   ['records' => $posts->toArray()],
            message: 'Latest 20 Blog Posts'
        );
    }

    public function show(ServerRequestInterface $request, $slug):  ActionResponse
    {
        $post = Post::where('slug', $slug)->first();

        if ($post) {
            return new ActionResponse(
                status: ActionResponse::SUCCESS,
                data:   ['record' => $post->toArray()],
                message: ''
            );
        }

        return new ActionResponse(
            status: ActionResponse::ERROR,
            message: '404 Not Found',
            statusCode: 404,
            template: 'errors/404'
        );
    }
}
