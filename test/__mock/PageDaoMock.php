<?php

use Obcato\Core\database\dao\PageDao;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\utilities\Arrays;

require_once CMS_ROOT . '/database/dao/PageDao.php';
require_once CMS_ROOT . '/utilities/Arrays.php';

class PageDaoMock implements PageDao {

    private array $pages = array();

    public function getAllPages(): array {
        // TODO: Implement getAllPages() method.
        return [];
    }

    public function getPage(?int $id): ?Page {
        // TODO: Implement getPage() method.
        return null;
    }

    public function getPageByElementHolderId(?int $elementHolderId): ?Page {
        foreach ($this->pages as $page) {
            if ($page->getId() === $elementHolderId) {
                return $page;
            }
        }
        return null;
    }

    public function getRootPage(): ?Page {
        // TODO: Implement getRootPage() method.
        return null;
    }

    public function getSubPages(Page $page): array {
        // TODO: Implement getSubPages() method.
        return [];
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
        return false;
    }

    public function isFirst(Page $page): bool {
        // TODO: Implement isFirst() method.
        return false;
    }

    public function searchByTerm(string $term): array {
        // TODO: Implement searchByTerm() method.
        return [];
    }

    public function moveUp(Page $page): void {
        // TODO: Implement moveUp() method.
    }

    public function moveDown(Page $page): void {
        // TODO: Implement moveDown() method.
    }

    public function getParent(Page $page): ?Page {
        // TODO: Implement getParent() method.
        return null;
    }

    public function getParents(Page $page): array {
        // TODO: Implement getParents() method.
        return [];
    }

    public function addPage(Page $page): void {
        $this->pages[] = $page;
    }

    public function getHomepage(): ?Page {
        // TODO: Implement getHomepage() method.
        return null;
    }
}