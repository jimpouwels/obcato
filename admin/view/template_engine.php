<?php

    
    defined('_ACCESS') or die;
        
    require_once CMS_ROOT . "view/smarty/Smarty.class.php";
    
    class TemplateEngine
    {
        private static $instance;

        private function __construct() {
        }

        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new Smarty();
                self::$instance->template_dir = BACKEND_TEMPLATE_DIR;
                self::$instance->compile_dir = BACKEND_TEMPLATE_DIR . "/compiled_templates";
                self::$instance->cache_dir = BACKEND_TEMPLATE_DIR . "/cache";
            }
            return self::$instance;
        }
    
    }
    
?>