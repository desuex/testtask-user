<?php

namespace App\Services;

use App\Application;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repository\UserRepository;
use App\Validators\CreateUserValidator;
use Exception;

class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getAllUsers($asArray = false): array
    {
        return $this->userRepository->all($asArray);
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

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function createUser(array $data): ?User
    {
        /** @var CreateUserValidator $validator */
        $validator = Application::get('create_user_validator');
        $errors = $validator->validate($data);
        if (!empty($errors)) {
            throw new ValidationException("Validation error when creating user!", $errors);
        }
        return $this->userRepository->createUser($data);
    }
}