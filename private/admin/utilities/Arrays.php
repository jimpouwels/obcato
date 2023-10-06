<?php

class Arrays {

    static function firstMatch(array $array, Closure $lambda): ?object {
        foreach ($array as $val) {
            if ($lambda($val)) {
                return $val;
            }
        }
        return null;
    }

}
