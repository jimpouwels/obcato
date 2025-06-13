<?php

namespace Obcato\Core\frontend;

class FrontendHelper {

    static function getQueryStringParam(string $name): ?string {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return "";
    }
}