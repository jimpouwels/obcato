<?php

namespace Obcato\Core\frontend\helper;

class FrontendHelper {

    static function isPreviewMode(): ?string {
        if (isset($_GET["mode"])) {
            return $_GET["mode"] == "preview";
        }
        return false;
    }

    public static function asPreviewUrl(?string $url): string
    {
        return $url . "?mode=preview";
    }
}