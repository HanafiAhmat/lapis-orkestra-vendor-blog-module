<?php declare(strict_types=1);

namespace Ampas\Blog\Checkers;

use Ampas\Blog\Entities\Category;
use Ampas\Blog\Entities\Post;
use Ampas\Blog\Entities\Tag;
use Ampas\Blog\Enums\PostStatus;
use BitSynama\Lapis\Framework\Checkers\AbstractChecker;
use BitSynama\Lapis\Framework\Persistences\AbstractEntity;
use BitSynama\Lapis\Framework\Validators\Input\RecordExistsValidator;
use BitSynama\Lapis\Framework\Validators\Input\UniqueSlugValidator;
use Laminas\Validator\BackedEnumValue;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\Validator\ValidatorChain;
use function array_values;
use function count;
use function in_array;
use function preg_replace;
use function strtolower;
use function trim;

class PostUpdateChecker extends AbstractChecker
{
    /** @var array<string, mixed> $inputs */
    protected array $inputs = [];

    public function __construct(
        protected string $categoryEntityClass = Category::class,
        protected string $postEntityClass = Post::class,
        protected string $postStatusEnumClass = PostStatus::class
    ) {
    }

    /**
     * Check if request inputs are valid.
     *
     * @param array<string, mixed> $inputs
     */
    public function isValid(array $inputs): bool
    {
        $isValid = true;

        // Title: required, 5–196 chars
        $titleValidator = new ValidatorChain();
        $titleValidator->attach(new NotEmpty([]), true);
        $titleValidator->attach(new StringLength(['min' => 5, 'max' => 196]), true);
        $title = $inputs['title'] ?? '';
        if (! $titleValidator->isValid($title)) {
            $isValid = false;
            $messages = array_values($titleValidator->getMessages());
            $this->errors['title'] = $messages[0] ?? 'Invalid title';
        }

        // Auto-generate slug from title if empty
        $slug = $inputs['slug'] ?? '';
        if (empty($slug) && ! empty($title)) {
            $slug = $this->generateSlug($title);
            $inputs['slug'] = $slug;
        }

        $currentId = 0;
        if ($this->entity instanceof AbstractEntity) {
            $currentId = $this->entity->getId();
        }
        // Slug: required, 5–254 chars
        $slugValidator = new ValidatorChain();
        $slugValidator->attach(new NotEmpty([]), true);
        $slugValidator->attach(new StringLength(['min' => 5, 'max' => 254]), true);
        $slugValidator->attach(new UniqueSlugValidator([
            'entityClass' => $this->postEntityClass,
            'currentId' => $currentId,
        ]));
        if (! $slugValidator->isValid($slug)) {
            $isValid = false;
            $messages = array_values($slugValidator->getMessages());
            $this->errors['slug'] = $messages[0] ?? 'Invalid or duplicate slug';
        }

        // Content: required
        $contentValidator = new ValidatorChain();
        $contentValidator->attach(new NotEmpty([]), true);
        if (! $contentValidator->isValid($inputs['content'] ?? '')) {
            $isValid = false;
            $messages = array_values($contentValidator->getMessages());
            $this->errors['content'] = $messages[0] ?? 'Content is required';
        }

        // Status: required + must be valid enum value
        $statusValidator = new ValidatorChain();
        $statusValidator->attach(new NotEmpty([]), true);
        $statusValidator->attach(new BackedEnumValue([
            'enum' => $this->postStatusEnumClass,
        ]));
        if (! $statusValidator->isValid($inputs['status'] ?? '')) {
            $isValid = false;
            $messages = array_values($statusValidator->getMessages());
            $this->errors['status'] = $messages[0] ?? 'Invalid status';
        }

        // blog_category_id: optional, but if present, must exist
        if (! empty($inputs['blog_category_id'])) {
            $categoryValidator = new ValidatorChain();
            $categoryValidator->attach(new RecordExistsValidator([
                'entityClass' => $this->categoryEntityClass,
            ]));
            if (! $categoryValidator->isValid($inputs['blog_category_id'])) {
                $isValid = false;
                $messages = array_values($categoryValidator->getMessages());
                $this->errors['blog_category_id'] = $messages[0] ?? 'Invalid category';
            }
        }

        // tags: optional, but if present, must exist
        if (! empty($inputs['tags']) && is_array($inputs['tags'])) {
            $tagValidator = new ValidatorChain();
            $tagValidator->attach(new Digits());
            $tagValidator->attach(new RecordExistsValidator([
                'entityClass' => Tag::class,
            ]));

            foreach ($inputs['tags'] as $i => $tagId) {
                if (! $tagValidator->isValid($tagId)) {
                    $isValid = false;
                    $this->errors['tags.' . $i] = array_values($tagValidator->getMessages())[0] ?? 'Invalid tag';
                }
            }
        }

        // Reassign inputs in case slug was modified
        $this->inputs = $inputs;

        return $isValid;
    }

    /**
     * Convert a title into a URL-safe slug.
     */
    protected function generateSlug(string $title): string
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $title);
        $slug = strtolower(trim($slug ?? '', '-'));
        return $slug;
    }
}
