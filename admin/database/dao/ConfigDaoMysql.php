<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/ConfigDao.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";

class ConfigDaoMysql implements ConfigDao {

    private static ?ConfigDaoMysql $instance = null;
    private SettingsDao $_settings_dao;

    private function __construct() {
        $this->_settings_dao = SettingsDaoMysql::getInstance();
    }

    public static function getInstance(): ConfigDaoMysql {
        if (!self::$instance) {
            self::$instance = new ConfigDaoMysql();
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