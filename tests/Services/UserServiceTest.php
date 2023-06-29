<?php

namespace Services;

use App\Models\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testAllUsersReturnsArrayOfUsersWhenUsersExists(): void
    {
        // Create a mock UserRepository
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $users = [new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com'])];
        // Define the behavior of the mock UserRepository
        $userRepositoryMock->expects($this->once())
            ->method('all')
            ->willReturn($users);

        // Create an instance of the UserService with the mock UserRepository
        $userService = new UserService($userRepositoryMock);

        // Call the method being tested
        $result = $userService->getAllUsers();

        // Assert that the result is an array of User model
        $this->assertEquals($result, $users);
    }


    public function testIsNameExistsReturnsTrueWhenNameExists(): void
    {
        // Create a mock UserRepository
        $userRepositoryMock = $this->createMock(UserRepository::class);

        // Define the behavior of the mock UserRepository
        $userRepositoryMock->expects($this->once())
            ->method('findByName')
            ->with('John')
            ->willReturn(new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com']));

        // Create an instance of the UserService with the mock UserRepository
        $userService = new UserService($userRepositoryMock);

        // Call the method being tested
        $result = $userService->isNameExists('John');

        // Assert that the result is true
        $this->assertTrue($result);
    }

    public function testIsNameExistsReturnsFalseWhenNameDoesNotExist(): void
    {
        // Create a mock UserRepository
        $userRepositoryMock = $this->createMock(UserRepository::class);

        // Define the behavior of the mock UserRepository
        $userRepositoryMock->expects($this->once())
            ->method('findByName')
            ->with('John')
            ->willReturn(null);

        // Create an instance of the UserService with the mock UserRepository
        $userService = new UserService($userRepositoryMock);

        // Call the method being tested
        $result = $userService->isNameExists('John');

        // Assert that the result is false
        $this->assertFalse($result);
    }

    public function testIsEmailExistsReturnsTrueWhenEmailExists(): void
    {
        // Create a mock UserRepository
        $userRepositoryMock = $this->createMock(UserRepository::class);

        // Define the behavior of the mock UserRepository
        $userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn(new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com']));

        // Create an instance of the UserService with the mock UserRepository
        $userService = new UserService($userRepositoryMock);

        // Call the method being tested
        $result = $userService->isEmailExists('john@example.com');

        // Assert that the result is true
        $this->assertTrue($result);
    }

    public function testIsEmailExistsReturnsFalseWhenEmailDoesNotExist(): void
    {
        // Create a mock UserRepository
        $userRepositoryMock = $this->createMock(UserRepository::class);

        // Define the behavior of the mock UserRepository
        $userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn(null);

        // Create an instance of the UserService with the mock UserRepository
        $userService = new UserService($userRepositoryMock);

        // Call the method being tested
        $result = $userService->isEmailExists('john@example.com');

        // Assert that the result is false
        $this->assertFalse($result);
    }


}