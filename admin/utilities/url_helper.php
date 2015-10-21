<?php
    defined('_ACCESS') or die;

    class UrlHelper {

        public static function addQueryStringParameter($url, $name, $value) {
            if (strpos('?', $url) !== false)
                return $url . '&' . $name . '=' . $value;
            else
                return $url . '?' . $name . '=' . $value;
        }

        public static function splitIntoParts($url) {
            $url = self::removeQueryStringFrom($url);
            return explode('/', $url);
        }

        public static function removeQueryStringFrom($url) {
            return strtok(rtrim($url, '/'), '?');
        }

        public static function removeLastPartFromUrl($url) {
            return substr($url, 0, strrpos($url, '/'));
        }

    }
