<?php

namespace App\Repository;


class UserLogRepository extends BaseRepository
{
    /**
     * @param $id
     * @param $action
     * @return bool
     */
    public function create($id, $action): bool
    {
        $query = 'INSERT INTO user_logs (user_id, action, timestamp) VALUES (:user_id, :action, :timestamp)';
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':user_id', $id);
        $statement->bindValue(':action', $action);
        $statement->bindValue(':timestamp', date('Y-m-d H:i:s'));
        return $statement->execute();
    }

}