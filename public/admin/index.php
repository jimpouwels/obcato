<?php

namespace Obcato;

// DIRECT ACCESS GRANTED
use Obcato\Core\authentication\Authenticator;
use Obcato\Core\Backend;
use Obcato\Core\request_handlers\StaticsRequestHandler;

require_once "../bootstrap.php";

if (!file_exists(PRIVATE_DIR . "/database_config.php") || isInstallMode()) {
    if (!isset($_GET["mode"])) {
        header("Location: /admin/login.php");
    }
    runInstaller();
} else {
    if (StaticsRequestHandler::isFileRequest()) {
        $staticsRequestHandler = new StaticsRequestHandler();
        if (!$staticsRequestHandler->isPublicFileRequest()) {
            Authenticator::isAuthenticated();
        }
        $staticsRequestHandler->handle();
    } else {
        Authenticator::isAuthenticated();
        $backend = new Backend();
        $backend->start();
    }
}

function runInstaller(): void {
    include "install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}