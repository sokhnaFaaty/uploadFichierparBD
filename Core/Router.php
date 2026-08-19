<?php

declare(strict_types=1);

namespace Core;

use Exceptions\AppException;
use Exceptions\NotFoundException;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container) {}

    public function get(string $path, array $action): void
    {
        $this->add('GET', $path, $action);
    }

    public function post(string $path, array $action): void
    {
        $this->add('POST', $path, $action);
    }

    private function add(string $method, string $path, array $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'path'   => $path,
            'action' => $action,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['path'], $path);
            if ($params === null) {
                continue;
            }

            try {
                [$class, $action] = $route['action'];
                $controller = $this->container->make($class);
                $output = $controller->{$action}(...$params);

                if (is_string($output)) {
                    echo $output;
                }
            } catch (AppException $e) {
                $this->handleException($e);
            }

            return;
        }

        http_response_code(404);
        echo View::render('errors/404', ['title' => 'Page introuvable'], null);
    }

    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '(\d+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        array_shift($matches);
        return array_map('intval', $matches);
    }

    private function handleException(AppException $e): void
    {
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            echo View::render('errors/404', ['title' => 'Page introuvable'], null);
            return;
        }

        // ValidationException (et tout le reste) : on revient en arrière avec un message
        flash('error', $e->getMessage());
        View::redirectBack('/');
    }
}
