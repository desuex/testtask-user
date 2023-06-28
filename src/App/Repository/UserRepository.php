<?php

namespace App\Repository;

class UserRepository extends BaseRepository
{
    public function all(): bool|array
    {
        $query = "SELECT * FROM users";
        $statement = $this->connection->query($query);
        return $statement->fetchAll();
    }
}