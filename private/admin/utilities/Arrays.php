<?php

class Arrays {

    static function firstMatch(array $array, Closure $lambda): object|array|string|null {
        $result = array_filter($array, $lambda);
        return $result[0] ?? null;
    }

}