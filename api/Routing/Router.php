<?php

namespace Api\Routing;

use InvalidArgumentException;

use const Api\Helpers\allowMethods;

use function Api\Helpers\dd;
use function Api\Helpers\jsonResponse;

class Router
{
    private array $routes = [];

    public function __call(
        string $method,
        array $args
    ): void {
        $method = strtoupper($method);
        [$path, $action] = $args;

        if (!in_array($method, allowMethods)) {
            throw new InvalidArgumentException("Method \"$method\" is not allowed.");
        }

        $this->addRoute($method, $path, $action);
    }

    public function addRoute(
        string $method,
        string $path,
        array $action
    ): void {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path);
        $this->routes[$method][] = [
            'pattern' => "#^" . rtrim($pattern, '/') . "$#",
            'action' => $action
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->execute($route['action'], $params);
                return;
            }
        }

        echo jsonResponse(['error' => 'Not Found'], 404);
    }

    private function execute(
        array $action,
        array $params = []
    ): mixed {
        if (is_array($action)) {
            [$class, $method] = $action;

            if (is_string($class)) {
                $class = new $class();
            }
            return $class->$method(...$params);
        }

        return $action(...$params);
    }
}
