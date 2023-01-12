<?php

declare(strict_types=1);

namespace App\Model;


use App\Model\Enum\ArticleOrder;
use Nette\Database\Row;
use Nette\Security\User;
use Nette\Utils\Paginator;

class Article extends Model
{

    public function getAll(Paginator $paginator, ArticleOrder $articleOrder, User $user) : array
    {
        return $this->database->fetchAll(
            'SELECT * FROM v_articles WHERE post_on<now() AND need_login IN (?) ORDER BY ?name DESC LIMIT ? OFFSET ?',
            [false, $user->isLoggedIn()],
            $articleOrder->value,
            $paginator->getLength(),
            $paginator->getCountdownOffset()
        );
    }

    public function getOne(int $id) : ?Row
    {
        return $this->database->fetch('SELECT * FROM v_articles WHERE id = ?',$id);
    }

    public function getCount(User $user): int
    {
        return $this->database->fetchField(
            'SELECT count(*) FROM v_articles WHERE post_on<now() AND need_login IN(?)',[false, $user->isLoggedIn()]);
    }

    public function rank(int $articleId, int $value, ?int $userId)
    {
        $this->database->query(
            'INSERT INTO article_rank(article_id, user_id, value) VALUES (?, ?, ?)',
            $articleId, $userId, $value);
    }
}