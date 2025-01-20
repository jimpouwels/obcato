<?php

namespace Obcato\Core\modules\images\model;

use Obcato\Core\core\model\Entity;

class Image extends Entity {

    private string $title;
    private ?string $altText = null;
    private ?string $filename;
    private ?string $thumbnailFilename;
    private ?string $location;
    private bool $published;
    private string $createdAt;
    private int $createdById;

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
        $this->setFilename($row['file_name']);
        $this->setThumbFileName($row['thumb_file_name']);
        $this->setLocation($row['location']);
        parent::initFromDb($row);
    }

    public function setCreatedById(int $createdById): void {
        $this->createdById = $createdById;
    }

    public function getCreatedById(): int {
        return $this->createdById;
    }

    public function setThumbFileName(?string $thumbnailFilename): void {
        $this->thumbnailFilename = $thumbnailFilename;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getAltText(): ?string {
        return $this->altText;
    }

    public function setAltText(?string $altText): void {
        $this->altText = $altText;
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
        return $this->thumbnailFilename;
    }

    public function isPublished(): bool {
        return $this->published;
    }

    public function setPublished(bool $published): void {
        $this->published = $published;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getExtension(): string {
        $parts = explode(".", $this->getFilename());
        return $parts[count($parts) - 1];
    }

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): void {
        $this->filename = $filename;
    }

    public function getLocation(): ?string {
        return $this->location;
    }

    public function setLocation(?string $location): void {
        $this->location = $location;
    }

}