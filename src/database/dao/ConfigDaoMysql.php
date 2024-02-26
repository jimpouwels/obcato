<?php

namespace Obcato\Core\database\dao;

use const use Obcato\Core\CONFIG_DIR;

class ConfigDaoMysql implements ConfigDao {

    private static ?ConfigDaoMysql $instance = null;

    public static function getInstance(): ConfigDaoMysql {
        if (!self::$instance) {
            self::$instance = new ConfigDaoMysql();
        }
        return self::$instance;
    }

    public function updateCaptchaSecret(string $captchaSecret): void {
        $filePath = CONFIG_DIR . "/captcha_secret.txt";
        $filePathNew = CONFIG_DIR . "/captcha_secret_new.txt";
        $newFile = fopen($filePathNew, "w");
        fwrite($newFile, $captchaSecret);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
        rename($filePathNew, $filePath);
    }

    public function getCaptchaSecret(): ?string {
        $filePath = CONFIG_DIR . "/captcha_secret.txt";
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        return null;
    }
}