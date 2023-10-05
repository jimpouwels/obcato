<?php
require_once "config.php";
if (defined('PRIVATE_DIRECTORY')) {
    define("PRIVATE_DIR", __DIR__ . '/' . PRIVATE_DIRECTORY);
} else {
    define("PRIVATE_DIR", __DIR__ . "/../private");
}
const CMS_ROOT = PRIVATE_DIR . "/admin";
const PUBLIC_DIR = __DIR__;