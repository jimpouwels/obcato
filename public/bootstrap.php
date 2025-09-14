<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\Backend;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\frontend\handlers\RequestHandler;
use Obcato\Core\request_handlers\StaticsRequestHandler;
use const Obcato\Core\UPLOAD_DIR;

$configFilePath = __DIR__ . "/config.php";
if (file_exists($configFilePath)) {
    require_once $configFilePath;
}

if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') {
    if (defined("PRIVATE_DIR_LOCAL")) {
        define("PRIVATE_DIR", __DIR__ . PRIVATE_DIR_LOCAL);
    } else {
        define("PRIVATE_DIR", __DIR__ . "/../private");
    }
} else {
    if (defined("PRIVATE_DIR_PRODUCTION")) {
        define("PRIVATE_DIR", __DIR__ . PRIVATE_DIR_PRODUCTION);
    } else {
        define("PRIVATE_DIR", __DIR__ . "/../private");
    }
}
const CMS_ROOT = PRIVATE_DIR . "/vendor/obcato/obcato/src";
const PUBLIC_DIR = __DIR__;

if (!file_exists(".htaccess")) {
    include_once CMS_ROOT . "/friendly_urls/FriendlyUrlManager.php";
    FriendlyUrlManager::writeHtaccessFileIfNotExists();
}

if (!defined("IS_TEST_RUN")) {
    require_once PRIVATE_DIR . "/database_config.php";
}
require CMS_ROOT . "/constants.php";
require PRIVATE_DIR . '/vendor/autoload.php';

function render(): void {
    if (isset($_GET["image"]) && $_GET['image'] != '') {
        loadImage();
    } else if (isset($_GET['download']) && $_GET['download'] != '') {
        // TODO
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/login')) {
        runLogin();
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin')) {
        runBackend();
    } else {
        runFrontend();
    }
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
}

function runInstaller(): void {
    include CMS_ROOT . "/install/index.php";
}

function isInstallMode(): bool {
    return isset($_GET["mode"]) && $_GET["mode"] == "install";
}

function loadImage(): void {
    if (isset($_GET['image']) && $_GET['image'] != '') {
        $imageDao = ImageDaoMysql::getInstance();
        $image = $imageDao->getImage($_GET['image']);

        if (!$image->isPublished())
            Authenticator::isAuthenticated();

        if (isset($_GET['thumb']) && $_GET['thumb'] == 'true') {
            $filename = $image->getThumbFileName();
        } else {
            $filename = $image->getFilename();
        }

        $path = UPLOAD_DIR . "/" . $filename;
        $splits = explode('.', $filename);
        $extension = $splits[count($splits) - 1];

        if ($extension == "jpg") {
            header("Content-Type: image/jpeg");
        } else if ($extension == "gif") {
            header("Content-Type: image/gif");
        } else if ($extension == "png") {
            header("Content-Type: img/png");
        } else {
            header("Content-Type: image/$extension");
        }

        readfile($path);
    }
}