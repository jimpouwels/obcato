<?php

namespace Obcato\Core\rest;

use Closure;

class Route {
    public HttpMethod $method;
    public string $path;
    public Closure $handlerFunction;

    public function __construct(HttpMethod $method, string $path, Closure $handlerFunction) {
        $this->method = $method;
        $this->path = $path;
        $this->handlerFunction = $handlerFunction;
    }
}