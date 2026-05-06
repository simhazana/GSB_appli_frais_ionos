<?php

namespace Core;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, callable|array $handler): void  { $this->routes['GET'][]  = [$path, $handler]; }
    public function post(string $path, callable|array $handler): void { $this->routes['POST'][] = [$path, $handler]; }

    public function dispatch(string $method, string $path): void {
        $method = strtoupper($method);

        foreach ($this->routes[$method] ?? [] as [$route, $handler]) {

            // Route regex
            if (is_string($route) && $route !== '' && $route[0] === '#') {
                if (preg_match($route, $path, $m)) {
                    array_shift($m);
                    $this->run($handler, $m);
                    return;
                }
                continue;
            }

            // Route exacte
            if ($route === $path) {
                $this->run($handler, []);
                return;
            }
        }

        http_response_code(404);
        echo '404';
    }

    private function run(callable|array $handler, array $params): void {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $obj = new $class();
            $obj->{$method}(...$params);
            return;
        }
        $handler(...$params);
    }
}



/*namespace Core;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, callable|array $handler): void  { $this->routes['GET'][$path]  = $handler; }
    public function post(string $path, callable|array $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $path): void {
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) { http_response_code(404); echo '404'; return; }
        if (is_array($handler)) { [$class, $m] = $handler; (new $class)->{$m}(); return; }
        $handler();
    }
}*/
