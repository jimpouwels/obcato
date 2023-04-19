<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/backend_request_handler.php";
    require_once CMS_ROOT . "view/views/cms.php";
    require_once CMS_ROOT . "view/views/popup.php";
    require_once CMS_ROOT . "text_resource_loader.php";

    class Backend {
    
        private $_identifier;
        private $_backend_request_handler;
        private $_current_module;
        private $_module_visual;
    
        public function __construct($identifier) {
            $this->_identifier = $identifier;
            $this->_backend_request_handler = new BackendRequestHandler();
        }
        
        public function start() {
            $this->loadTextResources();
            $this->_backend_request_handler->handle();
            $this->loadCurrentModule();
            $this->runModuleRequestHandler();
            $this->renderCms();
        }
        
        private function loadCurrentModule() {
            $current_module = $this->_backend_request_handler->getCurrentModule();
            if (!is_null($current_module)) {
                $this->_current_module = $current_module;
                require_once CMS_ROOT . "modules/" . $this->_current_module->getIdentifier() . "/activator.php";
                $class = $this->_current_module->getClass();
                $this->_module_visual = new $class($this->_current_module);
            }
        }
        
        private function renderCms() {
            if ($this->isPopupView()) {
                $this->renderPopupView();
            } else {
                $this->renderCmsView();
            }
        }
        
        private function renderCmsView() {
            $cms = new Cms($this->_module_visual, WEBSITE_TITLE);
            echo $cms->render();
        }

        private function renderPopupView() {
            $popup = new Popup($_GET['popup']);
            echo $popup->render();
        }
        
        private function runModuleRequestHandler() {
            if (!is_null($this->_module_visual)) {
                foreach ($this->_module_visual->getRequestHandlers() as $request_handler) {
                    $request_handler->handle();
                }
                $this->_module_visual->onRequestHandled();
            }
        }
        
        private function loadTextResources() {
            if (!Session::areTextResourcesLoaded()) {
                $text_resource_loader = new TextResourceLoader(Session::getCurrentLanguage());
                $text_resources = $text_resource_loader->loadTextResources();
                Session::setTextResources($text_resources);
            }
        }
                
        private function isPopupView() {
            return isset($_GET['popup']);
        }
        
    }