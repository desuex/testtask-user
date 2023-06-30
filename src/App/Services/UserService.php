<?php

namespace App\Services;

use App\Application;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repository\UserRepository;
use App\Validators\CreateUserValidator;
use App\Validators\UpdateUserValidator;
use Exception;

class UserService
{

    private UserRepository $userRepository;
    private User $currentUser;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param $asArray
     * @param $withDeleted
     * @return array
     */
    public function getAllUsers($asArray = false, $withDeleted = false): array
    {
        return $this->userRepository->all($asArray, $withDeleted);
    }

    /**
     * @param $name
     * @return bool
     */
    public function isNameExists($name): bool
    {
        $user = $this->userRepository->findByName($name);
        return $user instanceof User;
    }

    /**
     * @param $email
     * @return bool
     */
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

    /**
     * @param $id
     * @param array $data
     * @return User|null
     * @throws FileNotFoundException
     * @throws ValidationException
     * @throws Exception
     */
    public function updateUser($id, array $data): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            throw new FileNotFoundException("User not found");
        }
        $this->setCurrentUser($user);
        /** @var UpdateUserValidator $validator */
        $validator = Application::get('update_user_validator');
        $errors = $validator->validate($data);
        if (!empty($errors)) {
            throw new ValidationException("Validation error when updating user!", $errors);
        }
        return $this->userRepository->updateUser($user, $data);

    }

    /**
     * @param $id
     * @return bool
     * @throws FileNotFoundException
     */
    public function deleteUser($id): bool
    {
        $user = $this->userRepository->find($id);
        if (!$user instanceof User) {
            throw new FileNotFoundException("User not found");
        }
        return $this->userRepository->deleteUser($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function setCurrentUser(User $user): void
    {
        $this->currentUser = $user;
    }

    /**
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
}