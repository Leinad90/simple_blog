<?php

declare(strict_types=1);

namespace App\Model;


use Nette\Database\Connection;
use Nette\SmartObject;

class Model
{
    use SmartObject;

    public function __construct(protected Connection $database)
    {

    }
}