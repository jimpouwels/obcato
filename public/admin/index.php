<?php

namespace Obcato\Core;

// DIRECT ACCESS GRANTED
use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\Backend;

define("_ACCESS", "GRANTED");

require_once "../bootstrap.php";

if (!file_exists(PRIVATE_DIR . "/database_config.php") || isInstallMode()) {
    if (!isset($_GET["mode"])) {
        header("Location: /admin/login.php");
    }
    runInstaller();
} else {
    runBackend();
}

function runBackend(): void {
    checkAuthentication();
    $backend = new Backend();
    $backend->start();
}

function checkAuthentication(): void {
    if (!Authenticator::isAuthenticated()) {
        redirectToLoginPage();
    }
}

function redirectToLoginPage(): void {
    session_destroy();
    header('Location: /admin/login.php');
    exit();
}

function runInstaller(): void {
    include "install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}