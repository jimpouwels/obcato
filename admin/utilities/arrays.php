<?php

    defined('_ACCESS') or die;

    class Arrays {
     
        static function firstMatch(array $array, Closure $lambda): object|array|string|null {
            foreach ($array as $val) {
                if ($lambda($val)) {
                    return $val;
                }
            }
            return null;
        }

    }
?>