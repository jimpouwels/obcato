<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "request_handlers/backend_request_handler.php";
    require_once CMS_ROOT . "view/views/cms.php";
    require_once CMS_ROOT . "view/views/popup.php";
    require_once CMS_ROOT . "text_resource_loader.php";
    
    class Backend {
    
        private $_identifier;
        private $_request_handlers;
        private $_current_module;
        private $_module_visual;
    
        public function __construct($identifier) {
            $this->_identifier = $identifier;
            $this->initializeRequestHandlers();
        }
        
        public function start() {
            $this->isAuthenticated();
            $this->loadTextResources();
            $this->runRequestHandlers();
            $this->runModuleRequestHandler();
            $this->renderCms();
        }
        
        public function isAuthenticated() {
            if (!Authenticator::isAuthenticated())
                $this->redirectToLoginPage();
        }
        
        /*
            Callback invocation for BackendRequestHandler.
        */
        public function setCurrentModule($current_module) {
            if (!is_null($current_module)) {
                $this->_current_module = $current_module;
                require_once CMS_ROOT . "modules/" . $this->_current_module->getIdentifier() . "/activator.php";
                $class = $this->_current_module->getClass();
                $this->_module_visual = new $class($this->_current_module);
            }
        }
        
        private function renderCms() {
            if ($this->isPopupView())
                $this->renderPopupView();
            else
                $this->renderCmsView();
        }
        
        private function renderCmsView() {
            $cms = new Cms($this->_module_visual, WEBSITE_TITLE);
            $cms->render();
        }

        private function renderPopupView() {
            $popup = new Popup($_GET['popup']);
            $popup->render();
        }
        
        private function runModuleRequestHandler() {
            if (!is_null($this->_module_visual)) {
                foreach ($this->_module_visual->getRequestHandlers() as $request_handler)
                    $request_handler->handle();
                $this->_module_visual->onPreHandled();
            }
        }
        
        private function initializeRequestHandlers() {
            $this->_request_handlers = array();
            $this->_request_handlers[] = new BackendRequestHandler($this);
        }
        
        private function runRequestHandlers() {
            foreach ($this->_request_handlers as $request_handler)
                $request_handler->handle();
        }
        
        private function redirectToLoginPage() {
            session_destroy();
            $org_url = null;
            if ($_SERVER['REQUEST_URI'] != '/admin/')
                $org_url = '?org_url=' . urlencode($_SERVER['REQUEST_URI']);
            header('Location: /admin/login.php' . $org_url);
            exit();
        }

        private function loadTextResources() {
            TextResourceLoader::loadTextResources();
            TemplateEngine::getInstance()->assign("text_resources", TextResourceLoader::getTextResources());
        }
                
        private function isPopupView() {
            return isset($_GET['popup']);
        }
        
    }