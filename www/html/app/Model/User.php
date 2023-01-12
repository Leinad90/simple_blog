<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\UniqueConstraintViolationException;

class User extends Model
{

    public function save($name, $password): int
    {
        $data=[
            'name'=>$name,
            'password'=>$password
        ];
        try {
            $this->database->query('INSERT INTO users ?', $data);
        } catch (UniqueConstraintViolationException $e) {
            throw new UserExistsException(previous: $e);
        }
        return (int)$this->database->getInsertId();
    }
}