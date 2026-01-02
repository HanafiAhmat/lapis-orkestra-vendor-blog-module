<?php declare(strict_types=1);

namespace Ampas\Blog\Enums;

enum CommentStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Spam     = 'spam';
    case Deleted  = 'deleted';
}
