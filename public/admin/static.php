<?php

define("_ACCESS", "GRANTED");

require_once "../discovery.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once PRIVATE_DIR . "/database_config.php";
require_once CMS_ROOT . "/constants.php";
require_once CMS_ROOT . "/request_handlers/StaticsRequestHandler.php";

Authenticator::isAuthenticated();

$statics_request_handler = new StaticsRequestHandler();
$statics_request_handler->handle();