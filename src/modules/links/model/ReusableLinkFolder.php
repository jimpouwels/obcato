<?php

namespace Pageflow\Core\modules\links\model;

use Pageflow\Core\core\model\Entity;

class ReusableLinkFolder extends Entity {

    private string $name;
    private ?int $parentFolderId;
    private array $subFolders = [];
    private array $links = [];

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setParentFolderId(?int $parentFolderId): void {
        $this->parentFolderId = $parentFolderId;
    }

    public function getParentFolderId(): ?int {
        return $this->parentFolderId;
    }

    public function addSubFolder(ReusableLinkFolder $folder): void {
        $this->subFolders[] = $folder;
    }

    public function getSubFolders(): array {
        return $this->subFolders;
    }

    public function addLink(ReusableLink $link): void {
        $this->links[] = $link;
    }

    public function getLinks(): array {
        return $this->links;
    }

    public static function constructFromRecord(array $row): ReusableLinkFolder {
        $folder = new ReusableLinkFolder();
        $folder->initFromDb($row);
        return $folder;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setParentFolderId(isset($row['parent_folder_id']) ? (int)$row['parent_folder_id'] : null);
        parent::initFromDb($row);
    }
}
