<?php

namespace Services;

use App\Exceptions\FileNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repository\UserRepository;
use App\Services\LogService;
use App\Services\UserService;
use App\Validators\CreateUserValidator;
use App\Validators\UpdateUserValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @property UserRepository|UserRepository&MockObject|MockObject $userRepositoryMock
 * @property LogService|LogService&MockObject|MockObject $logServiceMock
 * @property CreateUserValidator|CreateUserValidator&MockObject|MockObject $createUserValidatorMock
 * @property UpdateUserValidator|UpdateUserValidator&MockObject|MockObject $updateUserValidatorMock
 * @property UserService $userService
 * @property array $userData
 * @property User $user
 * @property User[] $users
 */
class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        // Create a mock UserRepository
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        // Create a mock LogService
        $this->logServiceMock = $this->createMock(LogService::class);
        // Create a mock CreateUserValidator
        $this->createUserValidatorMock = $this->createMock(CreateUserValidator::class);
        // Create a mock UpdateUserValidator
        $this->updateUserValidatorMock = $this->createMock(UpdateUserValidator::class);


        // Create an instance of the UserService with the mock UserRepository and LogService
        $this->userService = new UserService($this->userRepositoryMock, $this->logServiceMock, $this->createUserValidatorMock, $this->updateUserValidatorMock);

        $this->userData = ['id' => 1, 'name' => 'johnsmith', 'email' => 'john@example.com'];
        $this->user = new User($this->userData);
        $this->users = [$this->user];

    }

    public function testGetUserReturnsUserWhenUserExists(): void
    {
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($this->user);

        // Call the method being tested
        $result = $this->userService->getUser(1);

        // Assert that the result is User model
        $this->assertEquals($result, $this->user);
    }

    public function testGetUserReturnsNullWhenUserDoesntExists(): void
    {
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        // Call the method being tested
        $result = $this->userService->getUser($this->user->getId());

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testCreateUserReturnsUserWhenUserIdCreated(): void
    {
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->willReturn($this->user);

        $this->createUserValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn([]); // No validation errors

        // Call the method being tested
        $result = $this->userService->createUser($this->userData);

        // Assert that the result is a valid User
        $this->assertEquals($result, $this->user);
    }

    public function testCreateUserThrowsValidationError(): void
    {
        $this->expectException(ValidationException::class);
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->never())
            ->method('createUser');

        $this->createUserValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn(['name' => ['Invalid name']]); // No validation errors

        // Call the method being tested
        $result = $this->userService->createUser($this->userData);

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testUpdateUserReturnsUpdatedUser(): void
    {
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->once())
            ->method('updateUser')
            ->willReturn($this->user);
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($this->user);

        $this->updateUserValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn([]); // No validation errors

        // Call the method being tested
        $result = $this->userService->updateUser($this->user, $this->userData);

        // Assert that the result is a valid User
        $this->assertEquals($result, $this->user);
    }

    public function testUpdateUserThrowsValidationError(): void
    {
        $this->expectException(ValidationException::class);
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->never())
            ->method('updateUser');
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($this->user);

        $this->updateUserValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn(['name' => ['Invalid name']]);

        // Call the method being tested
        $result = $this->userService->updateUser($this->user, $this->userData);

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testUpdateUserThrowsUserNotFound(): void
    {
        $this->expectException(FileNotFoundException::class);
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->never())
            ->method('updateUser');
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->updateUserValidatorMock->expects($this->never())
            ->method('validate');

        // Call the method being tested
        $result = $this->userService->updateUser($this->user, $this->userData);

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testDeleteUserReturnsTrueIfUserWasDeleted(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('deleteUser')
            ->willReturn(true);
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($this->user);

        // Call the method being tested
        $result = $this->userService->deleteUser($this->user->getId());

        // Assert that the result is true
        $this->assertTrue($result);
    }

    public function testDeleteUserReturnsThrowsErrorWhenUserDoesntExist(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->userRepositoryMock->expects($this->never())
            ->method('deleteUser');
        $this->userRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        // Call the method being tested
        $result = $this->userService->deleteUser($this->user->getId());

        // Assert that the result is true
        $this->assertTrue($result);
    }


    public function testAllUsersReturnsArrayOfUsersWhenUsersExists(): void
    {
        // Define the behavior of the mock UserRepository
        $this->userRepositoryMock->expects($this->once())
            ->method('all')
            ->willReturn($this->users);

        // Call the method being tested
        $result = $this->userService->getAllUsers();

        // Assert that the result is an array of User model
        $this->assertEquals($result, $this->users);
    }


}