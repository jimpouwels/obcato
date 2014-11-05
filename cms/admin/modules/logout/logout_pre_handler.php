<?php
    
    require_once CMS_ROOT . "core/data/session.php";
    require_once CMS_ROOT . "request_handlers/module_request_handler.php";
    
    class LogoutPreHandler extends ModuleRequestHandler {
    
        public function handleGet() {
            Session::logOut($_SESSION['username']);
        }
        
        public function handlePost() {
        }
        
    }
    
?>