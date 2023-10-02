<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "database/dao/authorization_dao.php";

class Download extends Entity {

    private AuthorizationDao $_authorization_dao;
    private string $_title;
    private string $_file_name;
    private bool $_published;
    private string $_created_at;
    private int $_created_by_id;

    public function __construct() {
        $this->_authorization_dao = AuthorizationDao::getInstance();
    }

    public static function constructFromRecord(array $row): Download {
        $download = new Download();
        $download->initFromDb($row);
        return $download;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setPublished($row['published']);
        $this->setCreatedAt($row['created_at']);
        $this->setCreatedById($row['created_by']);
        $this->setFileName($row['file_name']);
        parent::initFromDb($row);
    }

    public function setCreatedById(int $created_by_id): void {
        $this->_created_by_id = $created_by_id;
    }

    public function getTitle(): string {
        return $this->_title;
    }

    public function setTitle(string $title): void {
        $this->_title = $title;
    }

    public function isPublished(): bool {
        return $this->_published;
    }

    public function setPublished(bool $published): void {
        $this->_published = $published;
    }

    public function getCreatedAt(): string {
        return $this->_created_at;
    }

    public function setCreatedAt(string $created_at): void {
        $this->_created_at = $created_at;
    }

    public function getCreatedBy(): User {
        return $this->_authorization_dao->getUserById($this->_created_by_id);
    }

    public function getExtension(): string {
        $parts = explode(".", $this->getFileName());
        return $parts[count($parts) - 1];
    }

    public function getFileName(): string {
        return $this->_file_name;
    }

    public function setFileName(string $filename): void {
        $this->_file_name = $filename;
    }
}