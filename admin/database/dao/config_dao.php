<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "database/dao/settings_dao.php";

class ConfigDao {

    private static ?ConfigDao $instance = null;
    private SettingsDao $_settings_dao;

    private function __construct() {
        $this->_settings_dao = SettingsDao::getInstance();
    }

    public static function getInstance(): ConfigDao {
        if (!self::$instance) {
            self::$instance = new ConfigDao();
        }
        return self::$instance;
    }

    public function updateCaptchaSecret(string $captcha_secret): void {
        $config_dir = $this->_settings_dao->getSettings()->getConfigDir();
        $file_path = "{$config_dir}/captcha_secret.txt";
        $file_path_new = "{$config_dir}/captcha_secret_new.txt";
        $new_file = fopen($file_path_new, "w");
        fwrite($new_file, $captcha_secret);

        if (file_exists($file_path)) {
            unlink($file_path);
        }
        rename($file_path_new, $file_path);
    }

    public function getCaptchaSecret(): ?string {
        $config_dir = $this->_settings_dao->getSettings()->getConfigDir();
        $file_path = "{$config_dir}/captcha_secret.txt";
        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        }
        return null;
    }
}

?>