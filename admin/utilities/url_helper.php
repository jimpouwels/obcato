<?php
    defined('_ACCESS') or die;

    class UrlHelper {

        public static function addQueryStringParameter($url, $name, $value) {
            if (strpos('?', $url) !== false)
                return $url . '&' . $name . '=' . $value;
            else
                return $url . '?' . $name . '=' . $value;
        }

    }
