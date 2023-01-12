<?php

declare(strict_types=1);

namespace App\Facade;

use Nette\Database\Explorer;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class Authenticator implements \Nette\Security\Authenticator
{

    public function __construct(
        private readonly Explorer  $database,
        private readonly Passwords $passwords
    )
    {
    }

    public function authenticate(string $username, string $password): SimpleIdentity
    {
        $row = $this->database->table('users')
            ->where('name', $username)
            ->fetch();

        if ($row===null) {
            throw new AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($password, $row->password)) {
            throw new AuthenticationException('Invalid password.');
        }
        return new SimpleIdentity(
            $row->id,
            [],
            ['name' => $row->name]
        );
    }
}