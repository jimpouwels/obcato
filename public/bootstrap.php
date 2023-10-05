<?php
require_once 'config.php';

define("PRIVATE_DIR", __DIR__ . getPrivateDir());
define("CMS_ROOT", PRIVATE_DIR . "/admin");
define("PUBLIC_DIR", __DIR__);
require_once PRIVATE_DIR . "/database_config.php";
require_once CMS_ROOT . "/constants.php";
require_once CMS_ROOT . "/frontend/handlers/RequestHandler.php";

function renderWebsite(): void {
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}
