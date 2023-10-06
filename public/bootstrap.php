<?php

function renderFrontend(): void {
    require_once "discovery.php";
    require_once CMS_ROOT . "/frontend/handlers/RequestHandler.php";
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}

function renderBackend(): void {
    require_once "discovery.php";
}
