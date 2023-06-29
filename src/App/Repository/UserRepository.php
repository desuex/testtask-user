<?php

namespace App\Repository;

use App\Models\User;
use PDO;

class UserRepository extends BaseRepository
{
    public function all(): array
    {
        $users = [];
        $query = 'SELECT * FROM users';
        $result = $this->getConnection()->query($query);

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new User($row);
                $users[] = $user;
            }
        }

        return $users;
    }

    public function findByName($name): ?User
    {
        $query = 'SELECT * FROM users WHERE name = :name';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }

        return null;
    }

    public function findByEmail($email): ?User
    {
        $query = 'SELECT * FROM users WHERE email = :email';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }

        return null;
    }
}