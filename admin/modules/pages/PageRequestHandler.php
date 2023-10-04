<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/modules/pages/service/PageInteractor.php";
require_once CMS_ROOT . "/modules/pages/PageForm.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/ElementHolderRequestHandler.php";
require_once CMS_ROOT . "/request_handlers/exceptions/ElementHolderContainsErrorsException.php";
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

class PageRequestHandler extends ElementHolderRequestHandler {

    private static string $PAGE_ID_POST = "element_holder_id";
    private static string $PAGE_ID_GET = "page";
    private static int $FALLBACK_PAGE_ID = 1;

    private Page $currentPage;
    private PageDao $pageDao;
    private BlockDao $blockDao;
    private PageService $pageService;
    private FriendlyUrlManager $friendlyUrlManager;

    public function __construct() {
        parent::__construct();
        $this->pageDao = PageDaoMysql::getInstance();
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->pageService = PageInteractor::getInstance();
    }

    public function handleGet(): void {
        $this->currentPage = $this->getPageFromGetRequest();
    }

    public function handlePost(): void {
        parent::handlePost();
        $this->currentPage = $this->getPageFromPostRequest();
        if ($this->isUpdatePageAction()) {
            $this->updatePage();
        } else if ($this->isDeletePageAction()) {
            $this->deletePage();
        } else if ($this->isAddSubPageAction()) {
            $this->addSubPage();
        } else if ($this->isMoveUpAction()) {
            $this->moveUp();
        } else if ($this->isMoveDownAction()) {
            $this->moveDown();
        }
    }

    public function getCurrentPage(): ?Page {
        return $this->currentPage;
    }

    private function updatePage(): void {
        $pageForm = new PageForm($this->currentPage);
        try {
            $pageForm->loadFields();
            $this->pageService->addSelectedBlocks($this->currentPage, $pageForm->getSelectedBlocks());
            $this->deleteSelectedBlocksFromPage();
            $this->pageDao->updatePage($this->currentPage);
            $this->updateElementHolder($this->currentPage);
            $this->friendlyUrlManager->insertOrUpdateFriendlyUrlForPage($this->currentPage);
            $this->sendSuccessMessage($this->getTextResource('page_saved_message'));
        } catch (ElementHolderContainsErrorsException|FormException) {
            $this->sendErrorMessage($this->getTextResource('page_not_saved_error_message'));
        }
    }

    private function deleteSelectedBlocksFromPage(): void {
        $currentPageBlocks = $this->blockDao->getBlocksByPage($this->currentPage);
        foreach ($currentPageBlocks as $currentPageBlock) {
            if ($this->isBlockSelectedForDeletion($currentPageBlock)) {
                $this->blockDao->deleteBlockFromPage($currentPageBlock->getId(), $this->currentPage);
            }
        }
    }

    private function isBlockSelectedForDeletion(Block $currentPageBlock): bool {
        return isset($_POST["block_" . $this->currentPage->getId() . "_" . $currentPageBlock->getId() . "_delete"]);
    }

    private function deletePage(): void {
        $subPages = $this->pageDao->getSubPages($this->currentPage);
        foreach ($subPages as $subPage) {
            $this->pageDao->deletePage($subPage);
        }

        $this->pageDao->deletePage($this->currentPage);

        $parent = $this->pageDao->getParent($this->currentPage);
        $currentLevelPages = $this->pageDao->getSubPages($parent);
        $this->updateFollowUp($currentLevelPages);
        $this->sendSuccessMessage($this->getTextResource('page_deleted_message'));
        $this->redirectTo($this->getBackendBaseUrl() . "&page=1");
    }

    private function addSubPage(): void {
        $newPage = new Page();
        $newPage->setParentId($this->currentPage->getId());
        $newPage->setShowInNavigation(true);
        $newPage->setDescription($this->getTextResource('new_page_default_title'));
        $newPage->setNavigationTitle($this->getTextResource('new_page_default_navigation_title'));
        $newPage->setTitle($this->getTextResource('new_page_default_title'));
        $user = Authenticator::getCurrentUser();
        $newPage->setCreatedById($user->getId());
        $newPage->setType(ELEMENT_HOLDER_PAGE);
        $this->pageDao->persist($newPage);

        $parent = $this->pageDao->getPage($this->currentPage->getId());
        $current_level_pages = $this->pageDao->getSubPages($parent);
        $this->updateFollowUp($current_level_pages);

        $this->sendSuccessMessage($this->getTextResource('page_added_message'));
        $this->redirectTo($this->getBackendBaseUrl() . "&page=" . $newPage->getId());
    }

    private function moveUp(): void {
        $this->pageDao->moveUp($this->currentPage);
    }

    private function moveDown(): void {
        $this->pageDao->moveDown($this->currentPage);
    }

    private function updateFollowUp(array $pages): void {
        for ($i = 0; $i < count($pages); $i++) {
            $pages[$i]->setFollowUp($i);
            $this->pageDao->updatePage($pages[$i]);
        }
    }

    private function getPageFromPostRequest(): Page {
        return $this->pageDao->getPage($_POST[self::$PAGE_ID_POST]);
    }

    private function getPageFromGetRequest(): Page {
        if (isset($_GET[self::$PAGE_ID_GET])) {
            return $this->pageDao->getPage($_GET[self::$PAGE_ID_GET]);
        } else {
            return $this->pageDao->getPage(self::$FALLBACK_PAGE_ID);
        }
    }

    private function isUpdatePageAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
    }

    private function isDeletePageAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_page";
    }

    private function isAddSubPageAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "sub_page";
    }

    private function isMoveUpAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "move_up";
    }

    private function isMoveDownAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "move_down";
    }

}

?>
