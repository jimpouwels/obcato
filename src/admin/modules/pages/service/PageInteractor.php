<?php

namespace Obcato\Core\admin\modules\pages\service;

use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\authentication\Session;
use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\database\dao\PageDao;
use Obcato\Core\admin\database\dao\PageDaoMysql;
use Obcato\Core\admin\modules\pages\model\Page;

class PageInteractor implements PageService {

    private static ?PageInteractor $instance = null;

    private PageDao $pageDao;
    private BlockDao $blockDao;

    private function __construct() {
        $this->pageDao = PageDaoMysql::getInstance();
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public static function getInstance(): PageInteractor {
        if (!self::$instance) {
            self::$instance = new PageInteractor();
        }
        return self::$instance;
    }

    public function getHomepage(): Page {
        return $this->pageDao->getHomepage();
    }

    public function getPageById(int $id): ?Page {
        return $this->pageDao->getPage($id);
    }

    public function updatePage(Page $page): void {
        $this->pageDao->updatePage($page);
    }

    public function addSubPageTo(Page $page): Page {
        $newPage = new Page();
        $newPage->setParentId($page->getId());
        $newPage->setShowInNavigation(true);
        $newPage->setDescription(Session::getTextResource('new_page_default_title'));
        $newPage->setNavigationTitle(Session::getTextResource('new_page_default_navigation_title'));
        $newPage->setTitle(Session::getTextResource('new_page_default_title'));
        $user = Authenticator::getCurrentUser();
        $newPage->setCreatedById($user->getId());
        $newPage->setType(ELEMENT_HOLDER_PAGE);
        $this->pageDao->persist($newPage);

        $parent = $this->pageDao->getPage($page->getId());
        $currentLevelPages = $this->pageDao->getSubPages($parent);
        $this->updateFollowUp($currentLevelPages);
        return $newPage;
    }

    public function addSelectedBlocks(Page $page, array $selectedBlocks): void {
        if (count($selectedBlocks) == 0) return;
        $blocksForPage = $this->blockDao->getBlocksByPage($page);
        foreach ($selectedBlocks as $selectedBlock) {
            if (!array_filter($blocksForPage, fn($blockForPage) => $blockForPage->getTitle() == $selectedBlock)) {
                $this->blockDao->addBlockToPage($selectedBlock, $page);
            }
        }
    }

    public function getSubPages(Page $page): array {
        return $this->pageDao->getSubPages($page);
    }

    public function deletePage(Page $page): void {
        $this->deleteSubPagesOf($page);
        $this->pageDao->deletePage($page);

        $parent = $this->pageDao->getParent($page);
        $currentLevelPages = $this->pageDao->getSubPages($parent);
        $this->updateFollowUp($currentLevelPages);
    }

    public function moveUp(Page $page): void {
        $this->pageDao->moveUp($page);
    }

    public function moveDown(Page $page): void {
        $this->pageDao->moveDown($page);
    }

    public function getRootPage(): Page {
        return $this->pageDao->getRootPage();
    }

    public function getParents(Page $page): array {
        return $this->pageDao->getParents($page);
    }

    private function updateFollowUp(array $pages): void {
        for ($i = 0; $i < count($pages); $i++) {
            $pages[$i]->setFollowUp($i);
            $this->pageDao->updatePage($pages[$i]);
        }
    }

    private function deleteSubPagesOf(Page $page): void {
        foreach ($this->pageDao->getSubPages($page) as $subPage) {
            $this->pageDao->deletePage($subPage);
        }

    }
}