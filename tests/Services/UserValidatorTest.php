<?php

namespace Services;

use App\Models\User;
use App\Repository\UserRepository;
use App\Validators\UserValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @property UserRepository|UserRepository&MockObject|MockObject $userRepositoryMock
 * @property UserValidator $userValidator
 * @property array $userData
 * @property User $user
 */
class UserValidatorTest extends TestCase
{

    protected function setUp(): void
    {
        // Create a mock UserRepository
        $this->userRepositoryMock = $this->createMock(UserRepository::class);

        // Create an instance of the UserValidator with the mock UserRepository
        $this->userValidator = new UserValidator($this->userRepositoryMock);

        $this->userData = ['id' => 1, 'name' => 'johnsmith', 'email' => 'john@example.com'];
        $this->user = new User($this->userData);
    }

    private function invokeProtectedMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testIsNameAllowed(): void
    {
        $result = $this->invokeProtectedMethod($this->userValidator, 'isNameAllowed', ['John']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isNameAllowed', ['admin']);
        $this->assertFalse($result);
    }

    public function testIsNameUniqueWhenUserExists(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('findByName')
            ->with('existingName')
            ->willReturn($this->user);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isNameUnique', ['existingName']);
        $this->assertFalse($result);
    }

    public function testIsNameUniqueWhenUserNotExist(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('findByName')
            ->with('newName')
            ->willReturn(null);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isNameUnique', ['newName']);
        $this->assertTrue($result);
    }

    public function testIsEmailAllowed(): void
    {
        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailAllowed', ['test@example.com']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailAllowed', ['test@mail.ru']);
        $this->assertFalse($result);
    }

    public function testIsEmailValid(): void
    {
        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailValid', ['test@example.com']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailValid', ['invalidemail']);
        $this->assertFalse($result);
    }

    public function testIsEmailUniqueWhenUserExists(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('existing@example.com')
            ->willReturn(new User());

        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailUnique', ['existing@example.com']);
        $this->assertFalse($result);
    }

    public function testIsEmailUniqueWhenUserNotExists(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with('new@example.com')
            ->willReturn(null);
        $result = $this->invokeProtectedMethod($this->userValidator, 'isEmailUnique', ['new@example.com']);
        $this->assertTrue($result);
    }

    public function testIsDeletedValid(): void
    {
        $result = $this->invokeProtectedMethod($this->userValidator, 'isDeletedValid', [null, '2023-01-01']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isDeletedValid', ['2022-01-01', '2023-01-01']);
        $this->assertFalse($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isDeletedValid', ['2023-01-01', '2023-01-01']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isDeletedValid', ['2024-01-01', '2023-01-01']);
        $this->assertTrue($result);

        $result = $this->invokeProtectedMethod($this->userValidator, 'isDeletedValid', ['invalid-date', '2023-01-01']);
        $this->assertFalse($result);
    }
}