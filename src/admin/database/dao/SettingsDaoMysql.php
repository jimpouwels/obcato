<?php

namespace Obcato\Core;

class SettingsDaoMysql implements SettingsDao {

    private static ?SettingsDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): SettingsDaoMysql {
        if (!self::$instance) {
            self::$instance = new SettingsDaoMysql();
        }
        return self::$instance;
    }

    public function update(Settings $settings): void {
        $statement = $this->mysqlConnector->prepareStatement("UPDATE settings SET website_title = ?, 
                                                                                        backend_hostname = ?,
                                                                                        frontend_hostname = ?,
                                                                                        smtp_host = ?,
                                                                                        email_address = ?,
                                                                                        database_version = ?,
                                                                                        404_page_id = ?");
        $websiteTitle = $settings->getWebsiteTitle();
        $backendHostname = $settings->getBackEndHostname();
        $frontendHostname = $settings->getFrontEndHostname();
        $smtpHost = $settings->getSmtpHost();
        $emailAddress = $settings->getEmailAddress();
        $databaseVersion = $settings->getDatabaseVersion();
        $page404Id = $settings->get404PageId();
        $statement->bind_param("ssssssi", $websiteTitle,
            $backendHostname,
            $frontendHostname,
            $smtpHost,
            $emailAddress,
            $databaseVersion,
            $page404Id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function insert(Settings $settings): void {
        $statement = $this->mysqlConnector->prepareStatement("INSERT INTO settings (website_title, backend_hostname, frontend_hostname, smtp_host 
                    , email_address, database_version) VALUES ('Default', ?, ?, ?, ?, '" . SYSTEM_VERSION . "')");
        $backendHostname = $settings->getBackEndHostname();
        $frontendHostname = $settings->getFrontEndHostname();
        $smtpHost = $settings->getSmtpHost();
        $emailAddress = $settings->getEmailAddress();
        $statement->bind_param("ssss", $backendHostname, $frontendHostname, $smtpHost, $emailAddress);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getSettings(): ?Settings {
        $result = $this->mysqlConnector->executeQuery("SELECT * FROM settings");
        while ($row = $result->fetch_assoc()) {
            return Settings::constructFromRecord($row);
        }
        return null;
    }

    public function setHomepage(int $homepageId): void {
        $query1 = "UPDATE pages SET is_homepage = 0";
        $query2 = "UPDATE pages SET is_homepage = 1 WHERE element_holder_id = $homepageId";

        $this->mysqlConnector->executeQuery($query1);
        $this->mysqlConnector->executeQuery($query2);
    }

}
