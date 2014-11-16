<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "text_resource_loader.php";
    
    abstract class ModuleRequestHandler extends HttpRequestHandler {
                
        public function getCurrentTabId() {
            $this->getModuleTabFromGetRequest();
            return $this->getModuleTabFromSession();
        }
        
        public function getErrorCount() {
            global $errors;
            return count($errors);
        }

        protected function getTextResource($identifier) {
            TextResourceLoader::getTextResource($identifier);
        }
        
        private function getModuleTabFromGetRequest() {
            if (isset($_GET["module_tab"]))
                $_SESSION["module_tab"] = $_GET["module_tab"];
        }
        
        private function getModuleTabFromSession() {
            $current_module_tab = 0;
            if (isset($_SESSION["module_tab"]))
                $current_module_tab = $_SESSION["module_tab"];
            return $current_module_tab;
        }

    }