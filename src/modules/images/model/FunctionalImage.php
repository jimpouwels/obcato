<?php

namespace Pageflow\Core\modules\images\model;

use Pageflow\Core\core\BlackBoard;
use Pageflow\Core\core\model\Entity;

class FunctionalImage extends Entity {

    private string $title;
    private ?string $altText = null;
    private ?string $filename = null;
    private ?int $folderId = null;
    private bool $published = false;

    public static function constructFromRecord(array $row): FunctionalImage {
        $image = new FunctionalImage();
        $image->initFromDb($row);
        return $image;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setAltText($row['alt_text'] ?? null);
        $this->setFilename($row['file_name'] ?? null);
        $this->setFolderId(isset($row['folder_id']) ? (int)$row['folder_id'] : null);
        $this->setPublished(($row['published'] ?? 0) == 1);
        parent::initFromDb($row);
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

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): void {
        $this->filename = $filename;
    }

    public function getFolderId(): ?int {
        return $this->folderId;
    }

    public function setFolderId(?int $folderId): void {
        $this->folderId = $folderId;
    }

    public function isPublished(): bool {
        return $this->published;
    }

    public function setPublished(bool $published): void {
        $this->published = $published;
    }

    public function getUrl(): string {
        return BlackBoard::getFunctionalImageBaseUrl() . '/' . $this->getId();
    }
}
