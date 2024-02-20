<?php

namespace Obcato\Core;

class UrlHelper {

    public static function addQueryStringParameter(string $url, string $name, string $value): string {
        if (str_contains('?', $url)) {
            return $url . '&' . $name . '=' . $value;
        } else {
            return $url . '?' . $name . '=' . $value;
        }
    }

    public static function splitIntoParts(string $url): array {
        $url = self::removeQueryStringFrom($url);
        return explode('/', $url);
    }

    public static function removeQueryStringFrom(string $url): string {
        return strtok(rtrim($url, '/'), '?');
    }

    public static function removeLastPartFromUrl(string $url): string {
        return substr($url, 0, strrpos($url, '/'));
    }

}
