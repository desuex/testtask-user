<?php

namespace App\Services;

use App\Models\User;
use App\Repository\UserRepository;

class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->all();
    }

    public function isNameExists($name): bool
    {
        $user = $this->userRepository->findByName($name);
        return $user instanceof User;
    }

    public function isEmailExists($email): bool
    {
        $user = $this->userRepository->findByEmail($email);
        return $user instanceof User;
    }
}