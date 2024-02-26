<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\pages\model\Page;

interface PageDao {
    public function getHomepage(): ?Page;

    public function getAllPages(): array;

    public function getPage(?int $id): ?Page;

    public function getPageByElementHolderId(?int $elementHolderId): ?Page;

    public function getParent(Page $page): ?Page;

    public function getParents(Page $page): array;

    public function getRootPage(): ?Page;

    public function getSubPages(Page $page): array;

    public function persist(Page $page): void;

    public function updatePage(Page $page): void;

    public function deletePage(Page $page): void;

    public function isLast(Page $page): bool;

    public function isFirst(Page $page): bool;

    public function searchByTerm(string $term): array;

    public function moveUp(Page $page): void;

    public function moveDown(Page $page): void;
}