<?php

namespace Obcato\Core;

use Obcato\Core\admin\frontend\handlers\RequestHandler;

require_once "discovery.php";

function renderFrontend(): void {
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}
