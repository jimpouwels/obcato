<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\request_handlers\StaticsRequestHandler;

require_once "../bootstrap.php";

Authenticator::isAuthenticated();

$statics_request_handler = new StaticsRequestHandler();
$statics_request_handler->handle();