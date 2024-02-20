<?php

namespace Obcato\Core;

interface LinkDao {
    public function createLink(int $elementHolderId, $title): Link;

    public function persistLink(Link $newLink): void;

    public function getLinksForElementHolder(int $elementHolderId): array;

    public function deleteLink(Link $link): void;

    public function updateLink(Link $link): void;

    public function getBrokenLinks(): array;
}