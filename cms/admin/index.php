<?php

	// DIRECT ACCESS GRANTED
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');

    if (!file_exists("database_config.php") || isInstallMode()) {
        if (!isset($_GET["mode"]))
            header("Location: /admin/index.php?mode=install&step=1");
        runInstaller();
    } else {
        runBackend();
    }

    function runBackend() {
        require_once "database_config.php";
        require_once "includes.php";
        require_once "constants.php";
        require_once "backend.php";
        $backend = new Backend("site_administrator");
        $backend->start();
    }

    function runInstaller() {
        include "install/index.php";
    }

    function isInstallMode() {
        return isset($_GET["mode"]) && $_GET["mode"] == "install";
    }