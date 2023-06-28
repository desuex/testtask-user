<?php

namespace App\Repository;

use PDO;

class BaseRepository
{
    protected PDO $connection;

    /**
     * @throws \Exception
     */
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }
}