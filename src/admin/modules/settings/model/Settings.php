<?php

namespace Obcato\Core\admin\modules\settings\model;

use Obcato\Core\admin\core\model\Entity;
use Obcato\Core\admin\database\dao\PageDaoMysql;
use Obcato\Core\admin\modules\pages\model\Page;

class Settings extends Entity {
    private string $websiteTitle;
    private string $frontendHostname;
    private string $backendHostname;
    private string $emailAddress;
    private string $smtpHost;
    private string $databaseVersion;
    private ?Page $page404 = null;

    public function getWebsiteTitle(): string {
        return $this->websiteTitle;
    }

    public function setWebsiteTitle(string $websiteTitle): void {
        $this->websiteTitle = $websiteTitle;
    }

    public function getFrontEndHostname(): string {
        return $this->frontendHostname;
    }

    public function setFrontEndHostname(string $frontendHostname): void {
        $this->frontendHostname = $frontendHostname;
    }

    public function getBackEndHostname(): string {
        return $this->backendHostname;
    }

    public function setBackEndHostname(string $backendHostname): void {
        $this->backendHostname = $backendHostname;
    }

    public function getEmailAddress(): string {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void {
        $this->emailAddress = $emailAddress;
    }

    public function getSmtpHost(): string {
        return $this->smtpHost;
    }

    public function setSmtpHost(string $smtpHost): void {
        $this->smtpHost = $smtpHost;
    }

    public function getDatabaseVersion(): string {
        return $this->databaseVersion;
    }

    public function setDatabaseVersion(string $databaseVersion): void {
        $this->databaseVersion = $databaseVersion;
    }

    public function get404PageId(): ?int {
        return $this->page404?->getId();
    }

    public function getPage404(): ?Page {
        return $this->page404;
    }

    public function setPage404(?Page $page404): void {
        $this->page404 = $page404;
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
        $this->setPage404(PageDaoMysql::getInstance()->getPage($row['404_page_id']));
    }
}