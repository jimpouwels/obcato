<?php

    // DIRECT ACCESS GRANTED
    define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    if (!file_exists("database_config.php") || isInstallMode()) {
        if (!isset($_GET["mode"]))
            header("Location: /admin/index.php?mode=install&step=1");
        runInstaller();
    } else {
        runBackend();
    }

    function runBackend() {
        require_once CMS_ROOT . "database_config.php";
        require_once CMS_ROOT . "includes.php";
        require_once CMS_ROOT . "constants.php";
        require_once CMS_ROOT . "backend.php";
        $backend = new Backend("site_administrator");
        $backend->start();
    }

    function runInstaller() {
        include "install/index.php";
    }

    function isInstallMode() {
        return isset($_GET["mode"]) && $_GET["mode"] == "install";
    }