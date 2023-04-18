<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/logout/logout_request_handler.php";

    class LogoutModuleVisual extends ModuleVisual {
    
        private Module $_module;
        private LogoutRequestHandler $_logout_request_handler;
    
        public function __construct($module) {
            parent::__construct($module);
            $this->_module = $module;
            $this->_logout_request_handler = new LogoutRequestHandler();
        }
    
        public function render(): string {
            return "";
        }
    
        public function getActionButtons(): array {
            return array();
        }
        
        public function renderHeadIncludes(): string {
            return "";
        }
        
        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_logout_request_handler;
            return $request_handlers;
        }
        
        public function onRequestHandled(): void {
        }
    
    }
    
?>