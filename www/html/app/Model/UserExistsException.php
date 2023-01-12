<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\UniqueConstraintViolationException;

class UserExistsException extends \Exception
{

}