<?php

namespace Obcato;

use Obcato\Core\frontend\handlers\RequestHandler;

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

if (!defined("IS_TEST_RUN")) {
    require_once PRIVATE_DIR . "/database_config.php";
}
require CMS_ROOT . "/constants.php";
require PRIVATE_DIR . '/vendor/autoload.php';

function renderFrontend(): void {
    $request_handler = new RequestHandler();
    $request_handler->handleRequest();
}
