<?php
// core/Router.php

namespace Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $pattern,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        // Normalize URI
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        // Remove base path if needed
        $basePath = defined('BASE_PATH') ? BASE_PATH : '';
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = $uri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Filter named params
                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                // Run middleware
                foreach ($route['middleware'] as $mw) {
                    $mwClass = "App\\Middleware\\{$mw}";
                    if (class_exists($mwClass)) {
                        $result = (new $mwClass())->handle();
                        if ($result === false) return;
                    }
                }

                // Dispatch handler
                if (is_callable($route['handler'])) {
                    call_user_func($route['handler'], $params);
                    return;
                }

                if (is_array($route['handler'])) {
                    [$controllerClass, $method] = $route['handler'];
                    $fullClass = "App\\Controllers\\{$controllerClass}";
                    if (class_exists($fullClass)) {
                        $controller = new $fullClass();
                        call_user_func_array([$controller, $method], [$params]);
                        return;
                    }
                }

                if (is_string($route['handler'])) {
                    [$controllerClass, $method] = explode('@', $route['handler']);
                    $fullClass = "App\\Controllers\\{$controllerClass}";
                    if (class_exists($fullClass)) {
                        $controller = new $fullClass();
                        call_user_func_array([$controller, $method], [$params]);
                        return;
                    }
                }

                $this->abort(500, 'Invalid route handler');
                return;
            }
        }

        $this->abort(404, 'Page Not Found');
    }

    private function abort(int $code, string $message): void
    {
        http_response_code($code);
        $view = dirname(__DIR__) . "/app/Views/errors/{$code}.php";
        if (file_exists($view)) {
            require $view;
        } else {
            echo "<h1>{$code} - {$message}</h1>";
        }
    }
}
