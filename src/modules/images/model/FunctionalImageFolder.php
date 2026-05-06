<?php

namespace Pageflow\Core\modules\images\model;

use Pageflow\Core\core\model\Entity;

class FunctionalImageFolder extends Entity {

    private string $name;
    private ?int $parentFolderId;
    private array $subFolders = [];
    private array $images = [];

    public static function constructFromRecord(array $row): FunctionalImageFolder {
        $folder = new FunctionalImageFolder();
        $folder->initFromDb($row);
        return $folder;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setParentFolderId(isset($row['parent_folder_id']) ? (int)$row['parent_folder_id'] : null);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getParentFolderId(): ?int {
        return $this->parentFolderId;
    }

    public function setParentFolderId(?int $parentFolderId): void {
        $this->parentFolderId = $parentFolderId;
    }

    public function addSubFolder(FunctionalImageFolder $folder): void {
        $this->subFolders[] = $folder;
    }

    public function getSubFolders(): array {
        return $this->subFolders;
    }

    public function addImage(FunctionalImage $image): void {
        $this->images[] = $image;
    }

    public function getImages(): array {
        return $this->images;
    }
}
