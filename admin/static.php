<?php
    
    define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    require_once CMS_ROOT . "authentication/authenticator.php";
    require_once CMS_ROOT . "database_config.php";
    require_once CMS_ROOT . "constants.php";
    require_once CMS_ROOT . "request_handlers/statics_request_handler.php";

    Authenticator::isAuthenticated();
    
    $statics_request_handler = new StaticsRequestHandler();
    $statics_request_handler->handle();