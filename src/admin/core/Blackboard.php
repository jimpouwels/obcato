<?php

namespace Obcato\Core\admin\core;

class Blackboard {

    static ?int $MODULE_ID = null;
    static int $MODULE_TAB_ID = 0;

    private function __construct() {}

    public static function getBackendBaseUrl(): string {
        $baseUrl = sprintf("/admin/index.php?module_id=%s", Blackboard::$MODULE_ID);
        $baseUrl .= sprintf("&module_tab_id=%s", Blackboard::$MODULE_TAB_ID);
        return $baseUrl;
    }

    public static function getBackendBaseUrlWithoutTab(): string {
        return sprintf("/admin/index.php?module_id=%s", Blackboard::$MODULE_ID);
    }

    public static function getBackendBaseUrlRaw(): string {
        return "/admin/index.php";
    }
}