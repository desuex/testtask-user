<?php

namespace App\Services;

use App\Models\User;
use App\Repository\UserLogRepository;

class LogService
{

    private UserLogRepository $repository;

    /**
     * @param UserLogRepository $repository
     */
    public function __construct(UserLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param string $action
     * @return bool
     */
    public function log(User $user, string $action): bool
    {
        $id = $user->getId();
        return $this->repository->create($id, $action);
    }
}