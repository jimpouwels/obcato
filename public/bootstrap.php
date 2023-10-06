<?php
require_once "discovery.php";

function renderFrontend(): void {
    require_once CMS_ROOT . "/frontend/handlers/RequestHandler.php";
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}
