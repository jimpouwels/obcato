<?php
require_once "config.php";

if (defined("PRIVATE_DIR_LOCAL") && defined("PRIVATE_DIR_PRODUCTION")) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define("PRIVATE_DIR", __DIR__ . PRIVATE_DIR_LOCAL);
    } else {
        define("PRIVATE_DIR", __DIR__ . '/' . PRIVATE_DIR_PRODUCTION);
    }
} else {
    define("PRIVATE_DIR", __DIR__ . '/../private');
}
const CMS_ROOT = PRIVATE_DIR . "/admin";
const PUBLIC_DIR = __DIR__;