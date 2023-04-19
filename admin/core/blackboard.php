<?php
    defined('_ACCESS') or die;

    class BlackBoard {

        static ?int $MODULE_ID = null;
        static int $MODULE_TAB_ID = 0;

        private function __construct() {
        }

        public static function getBackendBaseUrl(): string {
            $base_url = sprintf("/admin/index.php?module_id=%s", BlackBoard::$MODULE_ID);
            $base_url .= sprintf("&module_tab_id=%s", BlackBoard::$MODULE_TAB_ID);
            return $base_url;
        }
        
        public static function getBackendBaseUrlWithoutTab(): string {
            return sprintf("/admin/index.php?module_id=%s", BlackBoard::$MODULE_ID);
        }
        
        public static function getBackendBaseUrlRaw(): string {
            return sprintf("/admin/index.php");
        }
    }


?>