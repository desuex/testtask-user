<?php

namespace App;

use App\Controllers\ControllerInterface;
use App\Exceptions\FileNotFoundException;
use App\Models\BaseModel;
use App\Response\JsonResponse;
use App\Response\ModelResponse;
use App\Response\ResponseInterface;
use App\Response\TextResponse;
use Exception;

class Route
{
    private array $routes;
    private Request $request;

    /**
     * @param Request $request
     * @param array $routes
     */
    public function __construct(Request $request, array $routes = [])
    {
        $this->request = $request;
        $this->routes = $routes;
    }

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function get(string $path, string $controller, string $action): void
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function post(string $path, string $controller, string $action): void
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function put(string $path, string $controller, string $action): void
    {
        $this->addRoute('PUT', $path, $controller, $action);
    }

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function patch(string $path, string $controller, string $action): void
    {
        $this->addRoute('PATCH', $path, $controller, $action);
    }

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function delete(string $path, string $controller, string $action): void
    {
        $this->addRoute('DELETE', $path, $controller, $action);
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    private function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    /**
     * @return array|null
     */
    public function matchRoute(): ?array
    {
        foreach ($this->routes as $route) {
            if (preg_match($this->buildRegex($route['path']), $this->request->getUri(), $matches)) {
                if ($this->request->getMethod() == $route['method']) {
                    $params = $this->extractParams($route['path'], $matches);
                    return [
                        'method' => $route['method'],
                        'controller' => $route['controller'],
                        'action' => $route['action'],
                        'params' => $params
                    ];
                }

            }
        }

        return null; // No matching route found
    }

    /**
     * @param $pattern
     * @return string
     */
    private function buildRegex($pattern): string
    {
        $regex = preg_replace('#\{(\w+)\}#', '(?<$1>[^/]+)', $pattern);
        return '#^' . str_replace('/', '\/', $regex) . '$#';
    }

    /**
     * @param $pattern
     * @param $matches
     * @return array
     */
    private function extractParams($pattern, $matches): array
    {
        $params = [];
        preg_match_all('/\{(\w+)\}/', $pattern, $paramNames);

        foreach ($paramNames[1] as $paramName) {
            if (isset($matches[$paramName])) {
                $params[$paramName] = $matches[$paramName];
            }
        }

        return $params;
    }

    /**
     * @return ResponseInterface
     * @throws FileNotFoundException
     */
    public function dispatch(): ResponseInterface
    {
        $route = $this->matchRoute();
        if (!is_null($route)) {
            $response = $this->callControllerAction($route['controller'], $route['action'], $route['params']);
            if (is_array($response)) {
                return new JsonResponse($response);
            } elseif ($response instanceof BaseModel) {
                return new ModelResponse($response);
            } else {
                return new TextResponse($response);
            }

        }
        // Handle 404 Not Found if no matching route is found
        throw new FileNotFoundException();

    }

    /**
     * @param string $controller
     * @param string $action
     * @param array $parameters
     * @return mixed
     * @throws FileNotFoundException
     * @throws Exception
     */
    private function callControllerAction(string $controller, string $action, array $parameters = []): mixed
    {
        $controllerInstance = Application::get($controller);

        if ($controllerInstance instanceof ControllerInterface && method_exists($controllerInstance, $action)) {
            return $controllerInstance->$action(...$parameters);
        } else {
            // Handle 404 Not Found if the action does not exist
            throw new FileNotFoundException();
        }
    }


}