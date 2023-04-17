<?php

    defined('_ACCESS') or die;

    include_once CMS_ROOT . "database/mysql_connector.php";
    include_once CMS_ROOT . "core/model/settings.php";

    class SettingsDao {

        private static ?SettingsDao $instance = null;
        private MysqlConnector $_mysql_database;

        private function __construct() {
            $this->_mysql_database = MysqlConnector::getInstance();
        }

        public static function getInstance(): SettingsDao {
            if (!self::$instance) {
                self::$instance = new SettingsDao();
            }
            return self::$instance;
        }

        public function update(Settings $settings): void {
            $mysql_database = MysqlConnector::getInstance();
            $query = "UPDATE settings SET website_title = '" . $settings->getWebsiteTitle() . "', backend_hostname = '" .
                     $settings->getBackEndHostname() . "', frontend_hostname = '" . $settings->getFrontEndHostname() . "',
                     smtp_host = '" . $settings->getSmtpHost() . "', email_address = '" . $settings->getEmailAddress() . "',
                     frontend_template_dir = '" . $settings->getFrontendTemplateDir() . "',
                     static_files_dir = '" . $settings->getStaticDir() . "', config_dir = '" . $settings->getConfigDir() . "',
                     upload_dir = '" . $settings->getUploadDir() . "', database_version = '" . $settings->getDatabaseVersion() . "',
                     component_dir = '" . $settings->getComponentDir() . "', backend_template_dir = '" . $settings->getBackendTemplateDir() . "',
                     cms_root_dir = '" . $settings->getCmsRootDir() . "', public_root_dir = '" . $settings->getPublicRootDir() . "'";
            $mysql_database->executeQuery($query);
        }
        
        public function insert(Settings $settings): void {
            $mysql_database = MysqlConnector::getInstance();
            $query = "INSERT INTO settings (website_title, backend_hostname, frontend_hostname, smtp_host 
                    , email_address, frontend_template_dir, config_dir, static_files_dir, upload_dir
                    , database_version, component_dir, backend_template_dir, cms_root_dir, public_root_dir) VALUES (
                    'Default','" . $settings->getBackendHostname() . "', '" . $settings->getFrontendHostname() . "','" .
                    $settings->getSmtpHost() . "', '" . $settings->getEmailAddress() . "','" . $settings->getFrontendTemplateDir() . "','" .
                     $settings->getConfigDir() . "','" . $settings->getStaticDir() . "','" . $settings->getUploadDir() . "','" . SYSTEM_VERSION ."','" . $settings->getComponentDir() . "','" . $settings->getBackendTemplateDir() . "', '" . 
                     $settings->getCmsRootDir() . "','" . $settings->getPublicRootDir() . "')";
            $mysql_database->executeQuery($query);
        }

        public function getSettings(): ?Settings {
            $result = $this->_mysql_database->executeQuery("SELECT * FROM settings");
            while ($row = $result->fetch_assoc()) {
                return Settings::constructFromRecord($row);
            }
            return null;
        }

        public function setHomepage(int $homepage_id): void {
            $this->_mysql_database = MysqlConnector::getInstance();
            $query1 = "UPDATE pages SET is_homepage = 0";
            $query2 = "UPDATE pages SET is_homepage = 1 WHERE element_holder_id = $homepage_id";

            $this->_mysql_database->executeQuery($query1);
            $this->_mysql_database->executeQuery($query2);
        }

    }
?>
