<?php

namespace Obcato;

// DIRECT ACCESS GRANTED
use Obcato\Core\authentication\Authenticator;
use Obcato\Core\Backend;

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
    $redirectTarget = '/admin/login.php';
    $uri = urlencode($_SERVER['REQUEST_URI']);
    if (str_contains($uri, 'module_id')) {
        $redirectTarget .= "?orgUrl={$uri}";
    }
    header("Location: {$redirectTarget}");
    exit();
}

function runInstaller(): void {
    include "install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}