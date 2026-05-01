<?php

namespace Pageflow\Core\modules\links\database\dao;

use Pageflow\Core\modules\links\model\ReusableLink;
use Pageflow\Core\modules\links\model\ReusableLinkFolder;

interface ReusableLinkDao {

    public function getLink(int $id): ?ReusableLink;
    public function getAllLinks(): array;
    public function getLinksByFolder(?int $folderId): array;
    public function searchLinks(string $keyword): array;
    public function createLink(ReusableLink $link): void;
    public function updateLink(ReusableLink $link): void;
    public function deleteLink(int $id): void;
    public function moveLinkToFolder(int $linkId, ?int $folderId): void;

    public function getFolder(int $id): ?ReusableLinkFolder;
    public function getAllFolders(): array;
    public function getRootFolders(): array;
    public function getFolderTree(): array;
    public function createFolder(ReusableLinkFolder $folder): void;
    public function updateFolder(ReusableLinkFolder $folder): void;
    public function deleteFolder(int $id): void;
}
