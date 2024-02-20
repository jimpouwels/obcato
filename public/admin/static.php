<?php

namespace Obcato\Core;

define("_ACCESS", "GRANTED");

require_once "../bootstrap.php";

require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/request_handlers/StaticsRequestHandler.php";

Authenticator::isAuthenticated();

$statics_request_handler = new StaticsRequestHandler();
$statics_request_handler->handle();