<?php
require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";

class Settings extends Entity {
    private string $_website_title;
    private string $_frontend_hostname;
    private string $_backend_hostname;
    private string $_email_address;
    private string $_smtp_host;
    private string $_database_version;
    private ?Page $_404_page = null;

    public static function find(): Settings {
        $mysql_database = MysqlConnector::getInstance();
        $result = $mysql_database->executeQuery("SELECT * FROM settings");
        $settings = null;
        while ($row = $result->fetch_assoc()) {
            $settings = self::constructFromRecord($row);
        }
        return $settings;
    }

    public static function constructFromRecord(array $row): Settings {
        $settings = new Settings();
        $settings->initFromDb($row);
        return $settings;
    }

    protected function initFromDb(array $row): void {
        $this->setWebsiteTitle($row['website_title']);
        $this->setFrontEndHostname($row['frontend_hostname']);
        $this->setBackEndHostname($row['backend_hostname']);
        $this->setEmailAddress($row['email_address']);
        $this->setSmtpHost($row['smtp_host']);
        $this->setDatabaseVersion($row['database_version']);
        $this->set404Page(PageDaoMysql::getInstance()->getPage($row['404_page_id']));
    }

    public function getWebsiteTitle(): string {
        return $this->_website_title;
    }

    public function setWebsiteTitle(string $website_title): void {
        $this->_website_title = $website_title;
    }

    public function getFrontEndHostname(): string {
        return $this->_frontend_hostname;
    }

    public function setFrontEndHostname(string $frontend_hostname): void {
        $this->_frontend_hostname = $frontend_hostname;
    }

    public function getBackEndHostname(): string {
        return $this->_backend_hostname;
    }

    public function setBackEndHostname(string $backend_hostname): void {
        $this->_backend_hostname = $backend_hostname;
    }

    public function getEmailAddress(): string {
        return $this->_email_address;
    }

    public function setEmailAddress(string $email_address): void {
        $this->_email_address = $email_address;
    }

    public function getSmtpHost(): string {
        return $this->_smtp_host;
    }

    public function setSmtpHost(string $smtp_host): void {
        $this->_smtp_host = $smtp_host;
    }

    public function getDatabaseVersion(): string {
        return $this->_database_version;
    }

    public function setDatabaseVersion(string $database_version): void {
        $this->_database_version = $database_version;
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
            $homepage = PageDaoMysql::getInstance()->getPage($homepage_id);
        }

        return $homepage;
    }

    public function get404PageId(): ?int {
        if ($this->_404_page) {
            return $this->_404_page->getId();
        }
        return null;
    }

    public function get404Page(): ?Page {
        return $this->_404_page;
    }

    public function set404Page(?Page $page_404): void {
        $this->_404_page = $page_404;
    }
}
