<?php

namespace Obcato\Core\rest;

use Closure;

abstract class Handler {

    private array $routes = array();

    public function register(HttpMethod $method, string $path, Closure $callback): void {
        $this->routes[] = new Route($method, $path, $callback);
    }

    public function handle(string $path): bool {
        header("Content-Type: application/json");
        foreach ($this->routes as $route) {
            if ($route->path == $path &&
                    (($_SERVER['REQUEST_METHOD'] == "POST" && $route->method == HttpMethod::POST)
                    || ($_SERVER['REQUEST_METHOD'] == "GET" && $route->method == HttpMethod::GET)
                    || ($_SERVER['REQUEST_METHOD'] == "DELETE" && $route->method == HttpMethod::DELETE)
                    || ($_SERVER['REQUEST_METHOD'] == "PUT" && $route->method == HttpMethod::PUT))) {
                $result = $route->handlerFunction->call($this, json_decode(file_get_contents('php://input'), true));
                echo json_encode($result, JSON_UNESCAPED_SLASHES);
                return true;
            }
        }
        return false;
    }

}