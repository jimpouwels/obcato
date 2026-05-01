<?php

namespace Pageflow\Core\modules\links\model;

use Pageflow\Core\core\model\Entity;

class ReusableLink extends Entity {

    private string $title;
    private ?string $url;
    private ?int $folderId;

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setUrl(?string $url): void {
        $this->url = $url;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setFolderId(?int $folderId): void {
        $this->folderId = $folderId;
    }

    public function getFolderId(): ?int {
        return $this->folderId;
    }

    public static function constructFromRecord(array $row): ReusableLink {
        $link = new ReusableLink();
        $link->initFromDb($row);
        return $link;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setUrl($row['url']);
        $this->setFolderId(isset($row['folder_id']) ? (int)$row['folder_id'] : null);
        parent::initFromDb($row);
    }
}
