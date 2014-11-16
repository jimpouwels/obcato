<?php

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "request_handlers/module_request_handler.php";
    
    class LogoutPreHandler extends ModuleRequestHandler {
    
        public function handleGet() {
            Authenticator::logOut($_SESSION['username']);
        }
        
        public function handlePost() {
        }
        
    }
    
?>