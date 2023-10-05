<?php
$configFileName = __DIR__ . '/config.php';
if (file_exists($configFileName)) {
    require_once $configFileName;
    define("CMS_ROOT", PRIVATE_DIR . "/admin");
    define("PUBLIC_DIR", __DIR__);
} else {
    echo "Site config (config.php) missing";
    exit();
}

