<?php

namespace App\Repository;

use App\Models\User;
use DateTime;
use PDO;

class UserRepository extends BaseRepository
{
    public function all($asArray = false): array
    {
        $users = [];
        $query = 'SELECT * FROM users';
        $result = $this->getConnection()->query($query);

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($asArray) {
                    $users[] = $row;
                } else {
                    $user = new User($row);
                    $users[] = $user;
                }

            }
        }

        return $users;
    }

    public function find($id): ?User
    {
        $query = 'SELECT * FROM users WHERE id = :id';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }

        return null;
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

    public function createUser($data): ?User
    {
        $query = 'INSERT INTO users (name, email, created) VALUES (:name, :email, :created)';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':email', $data['email']);

        $created = new DateTime();
        $createdFormatted = $created->format('Y-m-d H:i:s');
        $statement->bindParam(':created', $createdFormatted);

        $statement->execute();

        $userId = $this->getConnection()->lastInsertId();

        return $this->find($userId);
    }
}