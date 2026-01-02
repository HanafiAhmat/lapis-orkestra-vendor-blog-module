<?php declare(strict_types=1);

namespace Ampas\Blog\Controllers;

use BitSynama\Lapis\Framework\DTO\ActionResponse;
use BitSynama\Lapis\Lapis;
use Psr\Http\Message\ServerRequestInterface;

class BlogRollController
{
    public function __invoke(ServerRequestInterface $request):  ActionResponse
    {
        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data:   ['title' => 'Blog Roll'],
            message: ''
        );
    }
}
