<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'modules/webforms/handlers/email_form_handler.php';
    
    class WebFormHandlerManager {

        private array $_all_handlers = array();
        private static ?WebFormHandlerManager $_instance = null;

        private function __construct() {
            $this->_all_handlers[] = new EmailFormHandler();
        }

        public static function getInstance() {
            if (!self::$_instance) {
                self::$_instance = new WebFormHandlerManager();
            }
            return self::$_instance;
        }

        public function getHandler(string $type): FormHandler {
            $found_handler = null;
            foreach ($this->_all_handlers as $handler) {
                if ($handler->getType() == $type) {
                    $found_handler = $handler;
                }
            }
            return $found_handler;
        }

        public function getAllHandlers(): array {
            return $this->_all_handlers;
        }
    }
?>