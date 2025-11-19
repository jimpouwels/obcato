<?php

namespace Obcato\Core\frontend\helper;

use Obcato\Core\utilities\UrlHelper;

class FrontendHelper {

    static function isPreviewMode(): ?string {
        if (isset($_GET["mode"])) {
            return $_GET["mode"] == "preview";
        }
        return false;
    }

    public static function asPreviewUrl(?string $url): string {
        return UrlHelper::addQueryStringParameter($url, 'mode', 'preview');
    }
}