<?php

namespace App\Repository;

use App\Models\User;
use DateTime;
use PDO;

class UserRepository extends BaseRepository
{
    public function all(bool $asArray = false, bool $withDeleted = false): array
    {
        $users = [];
        $query = 'SELECT * FROM users';
        if (!$withDeleted) {
            $query .= ' WHERE deleted IS NULL';
        }
        $statement = $this->getConnection()->prepare($query);
        $statement->execute();

        if ($asArray) {
            $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $users[] = new User($row);
            }
        }

        return $users;
    }

    /**
     * @param $id
     * @return User|null
     */
    public function find($id, bool $withDeleted = false): ?User
    {
        $query = 'SELECT * FROM users WHERE id = :id';
        if (!$withDeleted) {
            $query .= ' AND deleted IS NULL';
        }
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }

        return null;
    }

    /**
     * @param $name
     * @return User|null
     */
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

    /**
     * @param $email
     * @return User|null
     */
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

    /**
     * @param $data
     * @return User|null
     */
    public function createUser($data): ?User
    {
        $query = 'INSERT INTO users (name, email, created, deleted) VALUES (:name, :email, :created, :deleted)';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':email', $data['email']);
        $statement->bindParam(':created', $data['deleted']);
        $created = new DateTime();
        $createdFormatted = $created->format('Y-m-d H:i:s');
        $statement->bindParam(':created', $createdFormatted);

        $statement->execute();

        $userId = $this->getConnection()->lastInsertId();

        return $this->find($userId);
    }

    /**
     * @param User $user
     * @param array $data
     * @return User|null
     */
    public function updateUser(User $user, array $data): ?User
    {
        $userId = $user->getId();
        $query = 'UPDATE users SET name = :name, email = :email, deleted = :deleted WHERE id = :id';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':email', $data['email']);
        $statement->bindParam(':deleted', $data['deleted']);
        $statement->bindParam(':id', $userId);

        $statement->execute();

        return $this->find($userId);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        $userId = $user->getId();
        $query = 'UPDATE users SET deleted = :deleted WHERE id = :id';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindParam(':id', $userId);
        $deleted = new DateTime();
        $deletedFormatted = $deleted->format('Y-m-d H:i:s');
        $statement->bindParam(':deleted', $deletedFormatted);

        return $statement->execute();
    }
}