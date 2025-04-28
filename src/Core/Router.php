<?php

namespace App\Core;

class Router
{
    private array $routes;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function registerRoute(string $method, string $path, callable|array $action): void
    {
        $this->routes[$method][$path] = $action;
    }

    public function get(string $path, callable|array $action): self
    {
        $this->registerRoute('get', $path, $action);
        return $this;
    }

    public function post(string $path, callable|array $action): self
    {
        $this->registerRoute('post', $path, $action);
        return $this;
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        $action = $this->routes[$method][$path];

        if (is_callable($action)) {
            echo call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class) && method_exists($class, $method)) {
                $class = new $class();
                echo call_user_func_array([$class, $method], ['req' => $this->request]);
            }
        }
    }
}