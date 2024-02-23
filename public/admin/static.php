<?php

namespace Obcato\Core;

use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\request_handlers\StaticsRequestHandler;

define("_ACCESS", "GRANTED");

require_once "../bootstrap.php";

Authenticator::isAuthenticated();

$statics_request_handler = new StaticsRequestHandler();
$statics_request_handler->handle();