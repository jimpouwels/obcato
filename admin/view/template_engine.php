<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/smarty/Smarty.class.php";

    class TemplateEngine
    {
        private static Smarty $_instance;
        private static bool $_initialized = false;

        private function __construct() {
        }

        public static function getInstance(): Smarty {
            if (!self::$_initialized) {
                self::$_instance = new Smarty();
                self::$_instance->template_dir = BACKEND_TEMPLATE_DIR;
                self::$_instance->compile_dir = BACKEND_TEMPLATE_DIR . "/compiled_templates";
                self::$_instance->cache_dir = BACKEND_TEMPLATE_DIR . "/cache";
                self::$_initialized = true;
            }
            return self::$_instance;
        }
    }
