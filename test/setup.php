<?php

define("IS_TEST_RUN", true);
define("PUBLIC_ROOT", __DIR__);
define("PRIVATE_ROOT", __DIR__ . "/..");
define("OBCATO_ROOT", PRIVATE_ROOT . "/src");

const CMS_ROOT = OBCATO_ROOT;

const MOCK_DIR = __DIR__ . "/__mock";
const HOST = "localhost";
const DATABASE_NAME = "obcato";
const USERNAME = "root";
const PASSWORD = "";