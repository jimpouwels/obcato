<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\Backend;
use Obcato\Core\frontend\handlers\RequestHandler;
use Obcato\Core\request_handlers\StaticsRequestHandler;

const PUBLIC_DIR = PUBLIC_ROOT;
const PRIVATE_DIR = PRIVATE_ROOT;
const CMS_ROOT = OBCATO_ROOT;

if (!file_exists(".htaccess")) {
    include_once CMS_ROOT . "/friendly_urls/FriendlyUrlManager.php";
    writeHtaccessFileIfNotExists();
}

if (!defined("IS_TEST_RUN")) {
    require_once PRIVATE_DIR . "/database_config.php";
}
require CMS_ROOT . "/constants.php";
require PRIVATE_DIR . '/vendor/autoload.php';

if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/update')) {
    runSystemUpdate();
} else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/login')) {
    runLogin();
} else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin') && StaticsRequestHandler::isFileRequest()) {
    $staticsRequestHandler = new StaticsRequestHandler();
    if (!$staticsRequestHandler->isPublicFileRequest()) {
        Authenticator::isAuthenticated();
    }
    $staticsRequestHandler->handle();
} else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin')) {
    runBackend();
} else {
    runFrontend();
}

function runSystemUpdate(): void {
    include CMS_ROOT . "/system_update.php";
}

function runLogin(): void {
    include CMS_ROOT . "/login.php";
}

function runFrontend(): void {
    $requestHandler = new RequestHandler();
    $requestHandler->handleRequest();
}

function runBackend(): void {
    if (!file_exists(PRIVATE_DIR . "/database_config.php") || isInstallMode()) {
        if (!isset($_GET["mode"])) {
            header("Location: /admin");
        }
        runInstaller();
    } else {
        Authenticator::isAuthenticated();
        $backend = new Backend();
        $backend->start();
    }
}

function runInstaller(): void {
    include CMS_ROOT . "/install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}

function writeHtaccessFileIfNotExists(): void {
    $htaccessFilePath = PUBLIC_DIR . '/.htaccess';
    if (file_exists($htaccessFilePath)) return;
    $handle = fopen($htaccessFilePath, 'w');
    fclose($handle);
    file_put_contents($htaccessFilePath, "RewriteEngine on\n\n" .
        "RewriteCond %{HTTP_HOST} !=localhost\n" .
        "RewriteCond %{HTTPS} !=on\n" .
        "RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n" .
        "RewriteCond %{HTTP_HOST} !^www\.\n" .
        "RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n" .
        "RewriteCond %{REQUEST_URI} !^/index.php\n" .
        "RewriteRule ^sitemap.xml$ /index.php?sitemap=true [NC,L]\n" .
        "RewriteRule ^robots.txt$ /index.php?robots=true [NC,L]\n\n" .
        "RewriteCond %{REQUEST_URI} !\.(.*)$\n" .
        "RewriteRule ^.*$ index.php [NC,L]");
}