<?php

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    
    class LogoutPreHandler extends HttpRequestHandler {
    
        public function handleGet() {
            Authenticator::logOut($_SESSION['username']);
        }
        
        public function handlePost() {
        }
        
    }
    
?>