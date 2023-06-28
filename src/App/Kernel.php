<?php

namespace App;

class Kernel
{
    private function __construct()
    {

    }

    /**
     * @throws \Exception
     */
    public static function bootstrap(): Application
    {
        define('PROJECT_ROOT', realpath(__DIR__ . '/../../'));

        // Autoload classes using Composer's autoloader
        require_once __DIR__ . '/../../vendor/autoload.php';

        $dotenv = \Dotenv\Dotenv::createImmutable(PROJECT_ROOT);
        $dotenv->load();

        // Create an instance of the application and return it
        $app = Application::getInstance();
        $app->boot();
        return $app;
    }
}