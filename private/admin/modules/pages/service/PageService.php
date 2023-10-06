<?php

interface PageService {
    public function getPageById(int $id): ?Page;

    public function updatePage(Page $page): void;

    public function addSelectedBlocks(Page $page, array $selectedBlocks): void;

    public function getSubPages(Page $page): array;

    public function addSubPageTo(Page $page): Page;

    public function moveUp(Page $page): void;

    public function moveDown(Page $page): void;

    public function deletePage(Page $page): void;
}