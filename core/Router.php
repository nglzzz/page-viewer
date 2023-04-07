<?php

declare(strict_types=1);

namespace Core;

final class Router
{
    private array $routes = [];
    private string $rotesPath = __DIR__ . '/../routes';

    public function __construct()
    {
        $this->loadRoutes();
    }

    /**
     * @param string $method
     * @param string $path
     * @param string|callable $handler Class name or callable function
     */
    public function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $request->getMethod() && $route['path'] === $request->getPath()) {
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    return $handler();
                }
                $class = new $handler();

                if ($class instanceof Controller) {
                    $class->setRequest($request);
                    $class->setResponse(new Response());
                    $class->setView(new View());
                }
                return $class();
            }
        }
        return false;
    }

    private function loadRoutes(): void
    {
        $files = array_diff(scandir($this->rotesPath), ['.', '..']);

        foreach ($files as $file) {
            $routes = require $this->rotesPath . '/' . $file;

            foreach ($routes as $route) {
                $this->addRoute($route['method'] ?? 'GET', $route['path'], $route['handler']);
            }
        }
    }
}
