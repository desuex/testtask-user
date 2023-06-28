<?php

namespace App;

use App\Controllers\UserController;
use App\Repository\BaseRepository;
use App\Repository\UserRepository;
use App\Response\JsonResponse;
use App\Response\TextResponse;
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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function boot(): void
    {
        if (empty($_ENV['DB_PATH'])) {
            throw new Exception('DB_PATH environment variable is required to run application!');
        }
        // Register the PDO service
        $this->container->register('pdo', PDO::class)
            ->addArgument('sqlite:' . PROJECT_ROOT . DIRECTORY_SEPARATOR . $_ENV['DB_PATH'])
            ->addArgument(null)
            ->addArgument(null)
            ->addArgument([]);
        // Register the BaseRepository service
        $this->container->register('base_repository', BaseRepository::class)
            ->addArgument(new Reference('pdo'));
        // Register the UserRepository service
        $this->container->register('user_repository', UserRepository::class)
            ->addArgument(new Reference('pdo'));
        // Register the UserController service
        $this->container->register('user_controller', UserController::class)
            ->addArgument(new Reference('user_repository'));
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
        /** @var UserController $controller */
        $controller = self::get('user_controller');
        $response = $controller->index();
        if(is_array($response)) {
            $response = new JsonResponse($response);
        } else {
            $response = new TextResponse($response);
        }
        $response->send();
    }

}