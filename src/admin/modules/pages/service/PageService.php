<?php

namespace Obcato\Core\admin\modules\pages\service;

use Obcato\Core\admin\modules\pages\model\Page;

interface PageService {
    public function getHomepage(): Page;

    public function getPageById(int $id): ?Page;

    public function updatePage(Page $page): void;

    public function addSelectedBlocks(Page $page, array $selectedBlocks): void;

    public function getSubPages(Page $page): array;

    public function addSubPageTo(Page $page): Page;

    public function moveUp(Page $page): void;

    public function moveDown(Page $page): void;

    public function deletePage(Page $page): void;

    public function getRootPage(): Page;

    public function getParents(Page $page): array;
}