<?php

require_once CMS_ROOT . '/database/dao/PageDao.php';
require_once CMS_ROOT . '/utilities/arrays.php';

class PageDaoMock implements PageDao {

    private array $_pages = array();

    public function getAllPages(): array {
        // TODO: Implement getAllPages() method.
    }

    public function getPage(?int $id): ?Page {
        // TODO: Implement getPage() method.
    }

    public function getPageByElementHolderId(?int $element_holder_id): ?Page {
        return Arrays::firstMatch($this->_pages, function ($page) use ($element_holder_id) {
            return $page->getId() == $element_holder_id;
        });
    }

    public function getRootPage(): ?Page {
        // TODO: Implement getRootPage() method.
    }

    public function getSubPages(Page $page): array {
        // TODO: Implement getSubPages() method.
    }

    public function persist(Page $page): void {
        // TODO: Implement persist() method.
    }

    public function updatePage(Page $page): void {
        // TODO: Implement updatePage() method.
    }

    public function deletePage(Page $page): void {
        // TODO: Implement deletePage() method.
    }

    public function isLast(Page $page): bool {
        // TODO: Implement isLast() method.
    }

    public function isFirst(Page $page): bool {
        // TODO: Implement isFirst() method.
    }

    public function searchByTerm(string $term): array {
        // TODO: Implement searchByTerm() method.
    }

    public function moveUp(Page $page): void {
        // TODO: Implement moveUp() method.
    }

    public function moveDown(Page $page): void {
        // TODO: Implement moveDown() method.
    }

    public function getParent(Page $page): ?Page {
        // TODO: Implement getParent() method.
    }

    public function getParents(Page $page): array {
        // TODO: Implement getParents() method.
    }

    public function addPage(Page $page): void {
        $this->_pages[] = $page;
    }
}