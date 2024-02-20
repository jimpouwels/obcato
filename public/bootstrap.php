<?php

namespace Obcato\Core;

require_once "discovery.php";

function renderFrontend(): void {
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}
