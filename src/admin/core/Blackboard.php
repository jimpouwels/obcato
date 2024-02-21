<?php

namespace Obcato\Core\admin\core;

use Obcato\Core\admin\core\model\Module;

class Blackboard {

    static Module $MODULE;
    static int $MODULE_TAB_ID = 0;

    private function __construct() {}

    public static function getBackendBaseUrl(): string {
        $baseUrl = sprintf("/admin/index.php?module_id=%s", Blackboard::$MODULE->getId());
        $baseUrl .= sprintf("&module_tab_id=%s", Blackboard::$MODULE_TAB_ID);
        return $baseUrl;
    }

    public static function getBackendBaseUrlWithoutTab(): string {
        return sprintf("/admin/index.php?module_id=%s", Blackboard::$MODULE->getId());
    }

    public static function getBackendBaseUrlRaw(): string {
        return "/admin/index.php";
    }

    public static function getCurrentModule(): Module {
        return self::$MODULE;
    }
}