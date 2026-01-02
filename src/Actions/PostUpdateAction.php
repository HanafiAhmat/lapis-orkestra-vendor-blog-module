<?php declare(strict_types=1);

namespace Ampas\Blog\Actions;

use Ampas\Blog\Checkers\PostUpdateChecker;
use Ampas\Blog\Entities\Post;
use Ampas\Blog\Entities\Category;
use Ampas\Blog\Entities\Tag;
use BitSynama\Lapis\Framework\Exceptions\NotFoundException;
use BitSynama\Lapis\Framework\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;

class PostUpdateAction
{
    public function __construct(
        protected ServerRequestInterface $request,
        protected int $id
    ) {}

    public function handle(): Post
    {
        $post = Post::find($this->id);
        dd($post);
        if (! ($post instanceof Post)) {
            throw new NotFoundException('Blog Post not found.');
        }

        $checker = new PostUpdateChecker();
        $inputs = $this->request->getParsedBody();
        if (! $checker->isValid($inputs)) {
            throw new ValidationException($checker->getErrors());
        }

        $validInputs = $checker->getInputs();

        $columns = $post->getConnection()
                        ->getSchemaBuilder()
                        ->getColumnListing($post->getTable());
        foreach ($validInputs as $key => $value) {
            if (in_array($key, $columns)) {
                $post->{$key} = $value;
            }
        }

        $user = $request->getAttribute('user');
        $post->updated_by_type = $user->user_type;
        $post->updated_by_id = $user->getId();
        $post->update();

        $tagIdsToBeAdded = [];
        if (count($validInputs['tag_ids']) > 0) {
            $tagIdsToBeAdded = array_diff($validInputs['tag_ids'], $post->tags->pluck('blog_tag_id')->toArray());
        }

        if ($post->tags->count() > 0) {
            if (count($validInputs['tag_ids']) > 0) {
                $tagIdsToBeRemoved = [];
                $post->tags->map(function ($postTag) use ($validInputs) {
                    if (!in_array($postTag->blog_tag_id, $validInputs['tag_ids'])) {
                        $tagIdsToBeRemoved[] = $postTag->blog_tag_id;
                    }
                });

                if (count($tagIdsToBeRemoved) > 0) {
                    $post->tags()->detach($tagIdsToBeRemoved);
                }
            } else {
                $post->tags()->detach();
            }
        }

        if (count($tagIdsToBeAdded) > 0) {
            $post->tags()->attach($tagIdsToBeAdded);
        }

        if ($auditLog = Lapis::interactorRegistry()->getOrSkip('plugin.system_monitor.audit_log')) {
            $auditLog::record($this->entityClass . ' updated', [
                'stored_entity_id' => $id,
                'user_type' => $user->user_type,
                'user_id' => $user->getId(),
                'client_type' => Lapis::requestUtility()->getClientType(),
                'user_agent' => Lapis::requestUtility()->getUserAgent(),
                'ip_address' => $request->getAttribute('client-ip'),
            ]);
        }

        return $post;
    }
}
