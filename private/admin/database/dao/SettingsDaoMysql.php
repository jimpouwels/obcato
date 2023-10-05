<?php
require_once CMS_ROOT . '/database/dao/SettingsDao.php';
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/modules/settings/model/Settings.php";

class SettingsDaoMysql implements SettingsDao {

    private static ?SettingsDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): SettingsDaoMysql {
        if (!self::$instance) {
            self::$instance = new SettingsDaoMysql();
        }
        return self::$instance;
    }

    public function update(Settings $settings): void {
        $statement = $this->_mysql_connector->prepareStatement("UPDATE settings SET website_title = ?, 
                                                                                        backend_hostname = ?,
                                                                                        frontend_hostname = ?,
                                                                                        smtp_host = ?,
                                                                                        email_address = ?,
                                                                                        database_version = ?,
                                                                                        404_page_id = ?");
        $website_title = $settings->getWebsiteTitle();
        $backend_hostname = $settings->getBackEndHostname();
        $frontend_hostname = $settings->getFrontEndHostname();
        $smtp_host = $settings->getSmtpHost();
        $email_address = $settings->getEmailAddress();
        $database_version = $settings->getDatabaseVersion();
        $page_404_id = $settings->get404PageId();
        $statement->bind_param("ssssssi", $website_title,
            $backend_hostname,
            $frontend_hostname,
            $smtp_host,
            $email_address,
            $database_version,
            $page_404_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function insert(Settings $settings): void {
        $query = "INSERT INTO settings (website_title, backend_hostname, frontend_hostname, smtp_host 
                    , email_address, database_version) VALUES (
                    'Default','" . $settings->getBackendHostname() . "', '" . $settings->getFrontendHostname() . "','" .
            $settings->getSmtpHost() . "', '" . $settings->getEmailAddress() . "','" . SYSTEM_VERSION . "')";
        $this->_mysql_connector->executeQuery($query);
    }

    public function getSettings(): ?Settings {
        $result = $this->_mysql_connector->executeQuery("SELECT * FROM settings");
        while ($row = $result->fetch_assoc()) {
            return Settings::constructFromRecord($row);
        }
        return null;
    }

    public function setHomepage(int $homepage_id): void {
        $query1 = "UPDATE pages SET is_homepage = 0";
        $query2 = "UPDATE pages SET is_homepage = 1 WHERE element_holder_id = $homepage_id";

        $this->_mysql_connector->executeQuery($query1);
        $this->_mysql_connector->executeQuery($query2);
    }

}

?>
