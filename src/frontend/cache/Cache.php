<?php

namespace Obcato\Core\frontend\cache;

use const Obcato\Core\CACHE_DIR;

class Cache {

    private static Cache $instance;

    private function __construct() {}

    public static function getInstance(): Cache {
        if (!isset(self::$instance)) {
            self::$instance = new Cache();
        }
        return self::$instance;
    }

    public function insert(string $originalUrl, string $html): void {

    }
}