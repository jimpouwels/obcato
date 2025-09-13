<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\request_handlers\StaticsRequestHandler;

require_once "../bootstrap.php";

$staticsRequestHandler = new StaticsRequestHandler();

if (!$staticsRequestHandler->isPublicFileRequest()) {
    Authenticator::isAuthenticated();
}

$staticsRequestHandler->handle();