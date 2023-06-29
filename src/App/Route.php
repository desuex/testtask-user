<?php

namespace App;

use App\Controllers\ControllerInterface;
use App\Exceptions\FileNotFoundException;
use App\Response\JsonResponse;
use App\Response\ResponseInterface;
use App\Response\TextResponse;

class Route
{
    private static array $routes = [];

    public static function get(string $path, string $controller, string $action)
    {
        self::addRoute('GET', $path, $controller, $action);
    }

    public static function post(string $path, string $controller, string $action)
    {
        self::addRoute('POST', $path, $controller, $action);
    }

    public static function put(string $path, string $controller, string $action)
    {
        self::addRoute('PUT', $path, $controller, $action);
    }

    public static function patch(string $path, string $controller, string $action)
    {
        self::addRoute('PATCH', $path, $controller, $action);
    }

    public static function delete(string $path, string $controller, string $action)
    {
        self::addRoute('DELETE', $path, $controller, $action);
    }

    private static function addRoute(string $method, string $path, string $controller, string $action): void
    {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public static function dispatch(string $requestMethod, string $requestPath): ResponseInterface
    {
        foreach (self::$routes as $route) {
            if ($route['method'] === $requestMethod && self::matchPath($route['path'], $requestPath)) {
                $response = self::callControllerAction($route['controller'], $route['action']);
                if (is_array($response)) {
                    return new JsonResponse($response);
                } else {
                    return new TextResponse($response);
                }
            }
        }

        // Handle 404 Not Found if no matching route is found
        throw new FileNotFoundException();
    }

    private static function matchPath(string $routePath, string $requestPath): bool
    {
        // Replace named parameters {param} with regex patterns for matching
        $pattern = preg_replace('/\{(\w+)\}/', '(?<$1>[^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return (bool)preg_match($pattern, $requestPath);
    }

    private static function callControllerAction(string $controller, string $action)
    {
        $controllerInstance = Application::get($controller);

        if ($controllerInstance instanceof ControllerInterface && method_exists($controllerInstance, $action)) {
            return $controllerInstance->$action();
        } else {
            // Handle 404 Not Found if the action does not exist
            throw new FileNotFoundException();
        }
    }


}