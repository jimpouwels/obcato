<?php

define("IS_TEST_RUN", true);
define("PUBLIC_ROOT", __DIR__);
define("PRIVATE_ROOT", __DIR__ . "/..");
define("PAGEFLOW_ROOT", PRIVATE_ROOT . "/src");

const CMS_ROOT = PAGEFLOW_ROOT;

const MOCK_DIR = __DIR__ . "/__mock";
const HOST = "localhost";
const DATABASE_NAME = "pageflow";
const USERNAME = "root";
const PASSWORD = "";