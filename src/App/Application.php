<?php

namespace App;

use App\Controllers\IndexController;
use App\Controllers\UserController;
use App\Repository\UserLogRepository;
use App\Repository\UserRepository;
use App\Services\LogService;
use App\Services\UserService;
use App\Validators\CreateUserValidator;
use App\Validators\UpdateUserValidator;
use Exception;
use PDO;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Application
{
    private static $instance;
    private ContainerBuilder $container;

    private function __construct()
    {
        $this->container = new ContainerBuilder();

    }

    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @throws Exception
     */
    public function boot(): void
    {
        if (empty($_ENV['DB_PATH'])) {
            throw new Exception('DB_PATH environment variable is required to run application!');
        }
        // Register the Request service
        $this->container->register('request', Request::class);

        // Register the Route service
        $this->container->register('route', Route::class)
            ->addArgument(new Reference('request'));

        // Register the PDO service
        $this->container->register('pdo', PDO::class)
            ->addArgument('sqlite:' . PROJECT_ROOT . DIRECTORY_SEPARATOR . $_ENV['DB_PATH'])
            ->addArgument(null)
            ->addArgument(null)
            ->addArgument([]);

        // Register the UserRepository
        $this->container->register('user_repository', UserRepository::class)
            ->addArgument(new Reference('pdo'));
        // Register the UserLogRepository
        $this->container->register('user_log_repository', UserLogRepository::class)
            ->addArgument(new Reference('pdo'));

        // Register the LogService
        $this->container->register('log_service', LogService::class)
            ->addArgument(new Reference('user_log_repository'));
        // Register the UserService
        $this->container->register('user_service', UserService::class)
            ->addArgument(new Reference('user_repository'))
            ->addArgument(new Reference('log_service'));


        // Register CreateUserValidator
        $this->container->register('create_user_validator', CreateUserValidator::class)
            ->addArgument(new Reference('user_service'));
        // Register UpdateUserValidator
        $this->container->register('update_user_validator', UpdateUserValidator::class)
            ->addArgument(new Reference('user_service'));

        // Register the UserController
        $this->container->register('user_controller', UserController::class)
            ->addArgument(new Reference('user_service'))
            ->addArgument(new Reference('request'));
        // Register the IndexController
        $this->container->register('index_controller', IndexController::class);

        $this->routes();

    }

    /**
     * @throws Exception
     */
    private function routes(): void
    {
        /** @var Route $route */
        $route = self::get('route');

        // Define the routes
        $route->get('/', 'index_controller', 'index');
        $route->get('/users', 'user_controller', 'index');
        $route->get('/users/{id}', 'user_controller', 'show');
        $route->post('/users', 'user_controller', 'create');
        $route->put('/users/{id}', 'user_controller', 'update');
        $route->delete('/users/{id}', 'user_controller', 'delete');
    }

    /**
     * @throws Exception
     */
    public static function get($serviceId): ?object
    {
        $app = self::getInstance();
        return $app->container->get($serviceId);
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        /** @var Route $route */
        $route = self::get('route');
        $response = $route->dispatch();
        $response->send();
    }

}