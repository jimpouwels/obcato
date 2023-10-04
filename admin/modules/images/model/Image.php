<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class Image extends Entity {

    private string $_title;
    private ?string $_alt_text = null;
    private ?string $_file_name;
    private ?string $_thumbnail_file_name;
    private bool $_published;
    private string $_created_at;
    private int $_created_by_id;

    public static function constructFromRecord(array $row): Image {
        $image = new Image();
        $image->initFromDb($row);
        return $image;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setAltText($row['alt_text']);
        $this->setPublished($row['published'] == 1);
        $this->setCreatedAt($row['created_at']);
        $this->setCreatedById($row['created_by']);
        $this->setFileName($row['file_name']);
        $this->setThumbFileName($row['thumb_file_name']);
        parent::initFromDb($row);
    }

    public function setCreatedById(int $created_by_id): void {
        $this->_created_by_id = $created_by_id;
    }

    public function setThumbFileName(?string $thumb_filename): void {
        $this->_thumbnail_file_name = $thumb_filename;
    }

    public function getTitle(): string {
        return $this->_title;
    }

    public function setTitle(string $title): void {
        $this->_title = $title;
    }

    public function getAltText(): ?string {
        return $this->_alt_text;
    }

    public function setAltText(?string $alt_text): void {
        $this->_alt_text = $alt_text;
    }

    public function getThumbUrl(): string {
        $id = $this->getId();
        return "/admin/upload.php?image=$id&amp;thumb=true";
    }

    public function getUrl(): string {
        $id = $this->getId();
        return "/admin/upload.php?image=$id";
    }

    public function getThumbFileName(): ?string {
        return $this->_thumbnail_file_name;
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
        $authorization_dao = AuthorizationDaoMysql::getInstance();
        return $authorization_dao->getUserById($this->_created_by_id);
    }

    public function getExtension(): string {
        $parts = explode(".", $this->getFileName());
        return $parts[count($parts) - 1];
    }

    public function getFileName(): ?string {
        return $this->_file_name;
    }

    public function setFileName(?string $filename): void {
        $this->_file_name = $filename;
    }

}