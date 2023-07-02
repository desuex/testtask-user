<?php

namespace Services;

use App\Models\User;
use App\Repository\UserLogRepository;
use App\Services\LogService;
use PHPUnit\Framework\TestCase;

class LogServiceTest extends TestCase
{

    public function testLogCallsUserLogRepositoryCreate(): void
    {
        // Create a mock UserLogRepository
        $userLogRepositoryMock = $this->createMock(UserLogRepository::class);

        // Define the behavior of the mock UserRepository
        $userLogRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn(true);

        // Create an instance of the LogService with the mock UserLogRepository
        $userService = new LogService($userLogRepositoryMock);
        $user = new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com']);
        // Call the method being tested
        $result = $userService->log($user, "action");

        // Assert that the result is true
        $this->assertTrue($result);
    }
}