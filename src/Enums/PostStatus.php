<?php declare(strict_types=1);

namespace Ampas\Blog\Enums;

enum PostStatus: string
{
    case Draft     = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived  = 'archived';

    public static function default(): self
    {
        return self::Draft;
    }
}
