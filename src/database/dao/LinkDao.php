<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\core\model\Link;

interface LinkDao {
    public function createLink(int $elementHolderId, $title): Link;

    public function getLink(int $id): ?Link;

    public function persistLink(Link $newLink): void;

    public function getLinksForElementHolder(int $elementHolderId): array;

    public function deleteLink(Link $link): void;

    public function updateLink(Link $link): void;

    public function getBrokenLinks(): array;
}