<?php declare(strict_types=1);

namespace Ampas\Blog\Interactors;

use Ampas\Blog\Entities\Post;
use BitSynama\Lapis\Framework\Contracts\InteractorInterface;
use BitSynama\Lapis\Lapis;
use function date;
use function json_encode;

class BlogInteractor implements InteractorInterface
{
    // private static AuditLog|null $auditLogInstance = null;

    /**
     * For test or injection: override default AuditLog instance.
     * This is used mainly for unit testing or mocking.
     */
    public static function latestPosts(AuditLog|null $instance): void
    {
        // self::$auditLogInstance = $instance;
    }
}
