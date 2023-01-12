<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum ArticleOrder: string
{
    case posted = 'post_on';
    case title = 'title';
    case rank = 'rank';
}