<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "authentication/authenticator.php";
require_once CMS_ROOT . "database/dao/page_dao.php";
require_once CMS_ROOT . "modules/pages/page_form.php";
require_once CMS_ROOT . "database/dao/block_dao.php";
require_once CMS_ROOT . "database/dao/element_dao.php";
require_once CMS_ROOT . "request_handlers/element_holder_request_handler.php";
require_once CMS_ROOT . "request_handlers/exceptions/element_holder_contains_errors_exception.php";
require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

class PageRequestHandler extends ElementHolderRequestHandler {

    private static string $PAGE_ID_POST = "element_holder_id";
    private static string $PAGE_ID_GET = "page";
    private static int $FALLBACK_PAGE_ID = 1;

    private Page $_current_page;
    private PageDao $_page_dao;
    private BlockDao $_block_dao;
    private FriendlyUrlManager $_friendly_url_manager;

    public function __construct() {
        parent::__construct();
        $this->_page_dao = PageDao::getInstance();
        $this->_block_dao = BlockDao::getInstance();
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
    }

    public function handleGet(): void {
        $this->_current_page = $this->getPageFromGetRequest();
    }

    public function handlePost(): void {
        parent::handlePost();
        $this->_current_page = $this->getPageFromPostRequest();
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
        return $this->_current_page;
    }

    private function updatePage(): void {
        $page_form = new PageForm($this->_current_page);
        try {
            $page_form->loadFields();
            $this->addSelectedBlocks($page_form->getSelectedBlocks());
            $this->deleteSelectedBlocksFromPage();
            $this->_page_dao->updatePage($this->_current_page);
            $this->updateElementHolder($this->_current_page);
            $this->_friendly_url_manager->insertOrUpdateFriendlyUrlForPage($this->_current_page);
            $this->sendSuccessMessage($this->getTextResource('page_saved_message'));
        } catch (ElementHolderContainsErrorsException $e) {
            $this->sendErrorMessage($this->getTextResource('page_not_saved_error_message'));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource('page_not_saved_error_message'));
        }
    }

    private function addSelectedBlocks(array $selected_blocks): void {
        if (is_null($selected_blocks) || count($selected_blocks) == 0) return;
        $current_page_blocks = $this->_current_page->getBlocks();
        foreach ($selected_blocks as $selected_block_id) {
            if (!$this->blockAlreadyExists($selected_block_id, $current_page_blocks)) {
                $this->_current_page->addBlock($this->_block_dao->getBlock($selected_block_id));
            }
        }
    }

    private function blockAlreadyExists(int $selected_block_id, array $current_page_blocks): bool {
        foreach ($current_page_blocks as $current_page_block) {
            if ($current_page_block->getId() == $selected_block_id) {
                return true;
            }
        }
        return false;
    }

    private function deleteSelectedBlocksFromPage(): void {
        $current_page_blocks = $this->_current_page->getBlocks();
        foreach ($current_page_blocks as $current_page_block) {
            if ($this->isBlockSelectedForDeletion($current_page_block)) {
                $this->_current_page->deleteBlock($current_page_block);
            }
        }
    }

    private function isBlockSelectedForDeletion(Block $current_page_block): bool {
        return isset($_POST["block_" . $this->_current_page->getId() . "_" . $current_page_block->getId() . "_delete"]);
    }

    private function deletePage(): void {
        $this->_page_dao->deletePage($this->_current_page);
        $parent = $this->_current_page->getParent();
        $current_level_pages = $parent->getSubPages();
        $this->updateFollowUp($current_level_pages);
        $this->sendSuccessMessage($this->getTextResource('page_deleted_message'));
        $this->redirectTo($this->getBackendBaseUrl() . "&page=1");
    }

    private function addSubPage(): void {
        $new_page = new Page();
        $new_page->setParentId($this->_current_page->getId());
        $new_page->setShowInNavigation(true);
        $new_page->setDescription($this->getTextResource('new_page_default_title'));
        $new_page->setNavigationTitle($this->getTextResource('new_page_default_navigation_title'));
        $new_page->setTitle($this->getTextResource('new_page_default_title'));
        $user = Authenticator::getCurrentUser();
        $new_page->setCreatedById($user->getId());
        $new_page->setType(ELEMENT_HOLDER_PAGE);
        $this->_page_dao->persist($new_page);

        $parent = $this->_page_dao->getPage($this->_current_page->getId());
        $current_level_pages = $parent->getSubPages();
        $this->updateFollowUp($current_level_pages);

        $this->sendSuccessMessage($this->getTextResource('page_added_message'));
        $this->redirectTo($this->getBackendBaseUrl() . "&page=" . $new_page->getId());
    }

    private function moveUp(): void {
        $this->_current_page->moveUp();
    }

    private function moveDown(): void {
        $this->_current_page->moveDown();
    }

    private function updateFollowUp(array $pages): void {
        for ($i = 0; $i < count($pages); $i++) {
            $pages[$i]->setFollowUp($i);
            $this->_page_dao->updatePage($pages[$i]);
        }
    }

    private function getPageFromPostRequest(): Page {
        return $this->_page_dao->getPage($_POST[self::$PAGE_ID_POST]);
    }

    private function getPageFromGetRequest(): Page {
        if (isset($_GET[self::$PAGE_ID_GET])) {
            return $this->_page_dao->getPage($_GET[self::$PAGE_ID_GET]);
        } else {
            return $this->_page_dao->getPage(self::$FALLBACK_PAGE_ID);
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
