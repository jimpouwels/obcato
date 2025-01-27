<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\request_handlers\StaticsRequestHandler;

require_once "../bootstrap.php";

Authenticator::isAuthenticated();

$staticsRequestHandler = new StaticsRequestHandler();
$staticsRequestHandler->handle();