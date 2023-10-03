<?php

define("_ACCESS", "GRANTED");
define("CMS_ROOT", dirname(__FILE__));

require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database_config.php";
require_once CMS_ROOT . "/constants.php";
require_once CMS_ROOT . "/request_handlers/StaticsRequestHandler.php";

Authenticator::isAuthenticated();

$statics_request_handler = new StaticsRequestHandler();
$statics_request_handler->handle();