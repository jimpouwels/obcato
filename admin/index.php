<?php

    // DIRECT ACCESS GRANTED
    define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    if (!file_exists("database_config.php") || isInstallMode()) {
        if (!isset($_GET["mode"])) {
            header("Location: /admin/index.php?mode=install&step=1");
        }
        runInstaller();
    } else {
        runBackend();
    }

    function runBackend() {
        require_once CMS_ROOT . "database_config.php";
        require_once CMS_ROOT . "includes.php";
        require_once CMS_ROOT . "constants.php";
        require_once CMS_ROOT . "backend.php";
        require_once CMS_ROOT . "authentication/authenticator.php";
        checkAuthentication();
        $backend = new Backend("site_administrator");
        $backend->start();
    }
        
    function checkAuthentication() {
        if (!Authenticator::isAuthenticated()) {
            redirectToLoginPage();
        }
    }
        
    function redirectToLoginPage() {
        session_destroy();
        $org_url = null;
        if ($_SERVER['REQUEST_URI'] != '/admin/') {
            $org_url = '?org_url=' . urlencode($_SERVER['REQUEST_URI']);
        }
        header('Location: /admin/login.php' . $org_url);
        exit();
    }

    function runInstaller() {
        include "install/index.php";
    }

    function isInstallMode() {
        return isset($_GET["mode"]) && $_GET["mode"] == "install";
    }