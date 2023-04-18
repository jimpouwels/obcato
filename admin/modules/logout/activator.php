<?php

    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "modules/logout/logout_pre_handler.php";

    class LogoutModuleVisual extends ModuleVisual {
    
        private $_module;
        private $_logout_pre_handler;
    
        public function __construct($module) {
            parent::__construct($module);
            $this->_module = $module;
            $this->_logout_pre_handler = new LogoutPreHandler();
        }
    
        public function render(): string {
            return "";
        }
    
        public function getActionButtons() {
        }
        
        public function renderHeadIncludes() {
        }
        
        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_logout_pre_handler;
            return $request_handlers;
        }
        
        public function onRequestHandled(): void {
        }
    
    }
    
?>