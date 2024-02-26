<?php

namespace Obcato\Core\core;

class BlackBoard {

    static ?int $MODULE_ID = null;
    static int $MODULE_TAB_ID = 0;

    private function __construct() {}

    public static function getBackendBaseUrl(): string {
        $baseUrl = sprintf("/admin/index.php?module_id=%s", BlackBoard::$MODULE_ID);
        $baseUrl .= sprintf("&module_tab_id=%s", BlackBoard::$MODULE_TAB_ID);
        return $baseUrl;
    }

    public static function getBackendBaseUrlWithoutTab(): string {
        return sprintf("/admin/index.php?module_id=%s", BlackBoard::$MODULE_ID);
    }

    public static function getBackendBaseUrlRaw(): string {
        return "/admin/index.php";
    }
}