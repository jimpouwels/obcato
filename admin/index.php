<?php

// DIRECT ACCESS GRANTED
define("_ACCESS", "GRANTED");
define("CMS_ROOT", dirname(__FILE__));

if (!file_exists("database_config.php") || isInstallMode()) {
    if (!isset($_GET["mode"])) {
        header("Location: /admin/index.php?mode=install&step=1");
    }
    runInstaller();
} else {
    runBackend();
}

function runBackend(): void {
    require_once CMS_ROOT . "/database_config.php";
    require_once CMS_ROOT . "/includes.php";
    require_once CMS_ROOT . "/constants.php";
    require_once CMS_ROOT . "/Backend.php";
    require_once CMS_ROOT . "/authentication/Authenticator.php";
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
    $org_url = null;
    if ($_SERVER['REQUEST_URI'] != '/admin/') {
        $org_url = '?org_url=' . urlencode($_SERVER['REQUEST_URI']);
    }
    header('Location: /admin/login.php' . $org_url);
    exit();
}

function runInstaller(): void {
    include "install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}