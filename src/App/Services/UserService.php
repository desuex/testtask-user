<?php

namespace App\Services;

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
    private ?User $currentUser;
    private LogService $logService;
    private CreateUserValidator $createUserValidator;
    private UpdateUserValidator $updateUserValidator;

    /**
     * @param UserRepository $userRepository
     * @param LogService $logService
     * @param CreateUserValidator $createUserValidator
     * @param UpdateUserValidator $updateUserValidator
     */
    public function __construct(UserRepository      $userRepository, LogService $logService,
                                CreateUserValidator $createUserValidator,
                                UpdateUserValidator $updateUserValidator)
    {
        $this->userRepository = $userRepository;
        $this->logService = $logService;
        $this->createUserValidator = $createUserValidator;
        $this->updateUserValidator = $updateUserValidator;
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
     * @param bool $asArray
     * @param bool $withDeleted
     * @return array
     */
    public function getAllUsers(bool $asArray = false, bool $withDeleted = false): array
    {
        return $this->userRepository->all($asArray, $withDeleted);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function createUser(array $data): ?User
    {
        /** @var CreateUserValidator $validator */
        $errors = $this->createUserValidator->validate($data);
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
        $errors = $this->updateUserValidator->validate($data);
        if (!empty($errors)) {
            throw new ValidationException("Validation error when updating user!", $errors);
        }
        $updatedUser = $this->userRepository->updateUser($user, $data);
        if ($updatedUser instanceof User) {
            $this->logService->log($updatedUser, "updated");
        }
        return $updatedUser;


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
        $success = $this->userRepository->deleteUser($user);
        if ($success) {
            $this->logService->log($user, "deleted");
        }
        return $success;
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
     * @return User
     * @throws Exception
     */
    public function getCurrentUser(): User
    {
        if (!$this->currentUser instanceof User) {
            throw new Exception("Current user is not set");
        }
        return $this->currentUser;
    }
}