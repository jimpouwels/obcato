<?php

namespace Obcato;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\Backend;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\frontend\handlers\RequestHandler;
use Obcato\Core\request_handlers\StaticsRequestHandler;
use Obcato\Core\utilities\UrlHelper;
use const Obcato\Core\UPLOAD_DIR;

const PUBLIC_DIR = PUBLIC_ROOT;
const PRIVATE_DIR = PRIVATE_ROOT;
const CMS_ROOT = OBCATO_ROOT;

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
    if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/image')) {
        $urlParts = UrlHelper::splitIntoParts($_SERVER['REQUEST_URI']);
        loadImage($urlParts[count($urlParts) - 1]);
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/download')) {
        // TODO
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/update')) {
        runSystemUpdate();
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/login')) {
        runLogin();
    } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin')) {
        runBackend();
    } else if (isset($_GET["image"])) {
        loadImage($_GET["image"]);
    } else {
        runFrontend();
    }
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

function loadImage(int $id): void {
    $imageDao = ImageDaoMysql::getInstance();
    $image = $imageDao->getImage($id);

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