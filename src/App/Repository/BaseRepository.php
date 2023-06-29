<?php

namespace App\Repository;

use Exception;
use PDO;

abstract class BaseRepository
{
    protected PDO $connection;

    /**
     * @throws Exception
     */
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    protected function getConnection(): PDO
    {
        return $this->connection;
    }
}