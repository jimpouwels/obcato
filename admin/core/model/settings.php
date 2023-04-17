<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";

    class Settings extends Entity {

        private string $_website_title;
        private string $_frontend_hostname;
        private string $_backend_hostname;
        private string $_email_address;
        private string $_smtp_host;
        private string $_cms_root_dir;
        private string $_public_root_dir;
        private string $_frontend_template_dir;
        private string $_static_dir;
        private string $_config_dir;
        private string $_upload_dir;
        private string $_component_dir;
        private string $_backend_template_dir;
        private string $_database_version;

        public function setWebsiteTitle(string $website_title): void {
            $this->_website_title = $website_title;
        }

        public function getWebsiteTitle(): string {
            return $this->_website_title;
        }

        public function setFrontEndHostname(string $frontend_hostname): void {
            $this->_frontend_hostname = $frontend_hostname;
        }

        public function getFrontEndHostname(): string {
            return $this->_frontend_hostname;
        }

        public function setBackEndHostname(string $backend_hostname): void {
            $this->_backend_hostname = $backend_hostname;
        }

        public function getBackEndHostname(): string {
            return $this->_backend_hostname;
        }

        public function setEmailAddress(string $email_address): void {
            $this->_email_address = $email_address;
        }

        public function getEmailAddress(): string {
            return $this->_email_address;
        }

        public function setSmtpHost(string $smtp_host): void {
            $this->_smtp_host = $smtp_host;
        }

        public function getSmtpHost(): string {
            return $this->_smtp_host;
        }

        public function getCmsRootDir(): string {
            return $this->_cms_root_dir;
        }

        public function setCmsRootDir(string $cms_root_dir): void {
            $this->_cms_root_dir = $cms_root_dir;
        }

        public function getPublicRootDir(): string {
          return $this->_public_root_dir;
        }

        public function setPublicRootDir(string $public_root_dir): void {
          $this->_public_root_dir = $public_root_dir;
        }

        public function setFrontendTemplateDir(string $frontend_template_dir): void {
            $this->_frontend_template_dir = $frontend_template_dir;
        }

        public function getFrontendTemplateDir(): string {
            return $this->_frontend_template_dir;
        }

        public function setStaticDir(string $static_dir): void {
            $this->_static_dir = $static_dir;
        }

        public function getStaticDir(): string {
            return $this->_static_dir;
        }

        public function setConfigDir(string $config_dir): void {
            $this->_config_dir = $config_dir;
        }

        public function getConfigDir(): string {
            return $this->_config_dir;
        }

        public function setUploadDir(string $upload_dir): void {
            $this->_upload_dir = $upload_dir;
        }

        public function getUploadDir(): string {
            return $this->_upload_dir;
        }

        public function setComponentDir(string $component_dir): void {
            $this->_component_dir = $component_dir;
        }

        public function getComponentDir(): string {
            return $this->_component_dir;
        }

        public function setDatabaseVersion(string $database_version): void {
            $this->_database_version = $database_version;
        }

        public function getDatabaseVersion(): string {
            return $this->_database_version;
        }

        public function setBackendTemplateDir(string $backend_template_dir): void {
            $this->_backend_template_dir = $backend_template_dir;
        }

        public function getBackendTemplateDir(): string {
            return $this->_backend_template_dir;
        }

        public function getHomepage(): Page {
            $mysql_database = MysqlConnector::getInstance();
            $result = $mysql_database->executeQuery("SELECT element_holder_id FROM pages WHERE is_homepage = 1");
            $homepage = null;
            $homepage_id = null;
            while ($row = $result->fetch_assoc()) {
                $homepage_id = $row['element_holder_id'];
            }
            if (!is_null($homepage_id)) {
                $homepage = PageDao::getInstance()->getPage($homepage_id);
            }

            return $homepage;
        }

        public static function find(): Settings {
            $mysql_database = MysqlConnector::getInstance();
            $result = $mysql_database->executeQuery("SELECT * FROM settings");
            $settings = null;
            while ($row = $result->fetch_assoc()) {
                $settings = self::constructFromRecord($row);
            }
            return $settings;
        }

        public static function constructFromRecord(array $record): Settings {
            $settings = new Settings();
            $settings->setWebsiteTitle($record['website_title']);
            $settings->setFrontEndHostname($record['frontend_hostname']);
            $settings->setBackEndHostname($record['backend_hostname']);
            $settings->setEmailAddress($record['email_address']);
            $settings->setSmtpHost($record['smtp_host']);
            $settings->setCmsRootDir($record['cms_root_dir']);
            $settings->setPublicRootDir($record['public_root_dir']);
            $settings->setFrontendTemplateDir($record['frontend_template_dir']);
            $settings->setStaticDir($record['static_files_dir']);
            $settings->setConfigDir($record['config_dir']);
            $settings->setUploadDir($record['upload_dir']);
            $settings->setComponentDir($record['component_dir']);
            $settings->setDatabaseVersion($record['database_version']);
            $settings->setBackendTemplateDir($record['backend_template_dir']);
            return $settings;
        }
    }

?>
