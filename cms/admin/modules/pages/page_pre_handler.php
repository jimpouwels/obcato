<?php
	// No direct access
	defined('_ACCESS') or die;
		
	require_once CMS_ROOT . "/database/dao/page_dao.php";
	require_once CMS_ROOT . "/modules/pages/page_form.php";
	require_once CMS_ROOT . "/database/dao/block_dao.php";
	require_once CMS_ROOT . "/database/dao/element_dao.php";
	require_once CMS_ROOT . "/database/dao/authorization_dao.php";
	require_once CMS_ROOT . "/libraries/system/notifications.php";
	require_once CMS_ROOT . "/view/request_handlers/element_holder_request_handler.php";
	
	class PagePreHandler extends ElementHolderRequestHandler {
	
		private static $PAGE_ID_POST = "element_holder_id";
		private static $PAGE_ID_GET = "page";
		private static $FALLBACK_PAGE_ID = 1;
	
		private $_current_page;
		private $_page_dao;
		private $_block_dao;
		private $_element_dao;
		
		public function __construct() {
            parent::__construct();
			$this->_page_dao = PageDao::getInstance();
			$this->_block_dao = BlockDao::getInstance();
			$this->_element_dao = ElementDao::getInstance();
		}
	
		public function handleGet() {
			$this->_current_page = $this->getPageFromGetRequest();
		}
		
		public function handlePost() {
            parent::handlePost();
			$this->_current_page = $this->getPageFromPostRequest();
			if ($this->isUpdatePageAction())
				$this->updatePage();
			else if ($this->isDeletePageAction())
				$this->deletePage();
			else if ($this->isAddSubPageAction())
				$this->addSubPage();
			else if ($this->isMoveUpAction())
				$this->moveUp();
			else if ($this->isMoveDownAction())
				$this->moveDown();
		}
		
		public function getCurrentPage() {
			return $this->_current_page;
		}
		
		private function updatePage() {
			$page_form = new PageForm($this->_current_page);
			try {
				$page_form->loadFields();
				$this->_element_dao->updateElementOrder($page_form->getElementOrder(), $this->_current_page);
				$this->addSelectedBlocks($page_form->getSelectedBlocks());
				$this->deleteSelectedBlocksFromPage();
				$this->_page_dao->updatePage($this->_current_page);
				Notifications::setSuccessMessage("Pagina succesvol opgeslagen");
			} catch (FormException $e) {
				Notifications::setFailedMessage("Pagina niet opgeslagen, verwerk de fouten");
			}
		}
		
		private function addSelectedBlocks($selected_blocks) {
			if (count($selected_blocks) == 0) return;
			$current_page_blocks = $this->_current_page->getBlocks();
			foreach ($selected_blocks as $selected_block_id) {
				if (!$this->blockAlreadyExists($selected_block_id, $current_page_blocks)) {
					$this->_current_page->addBlock($this->_block_dao->getBlock($selected_block_id));
				}
			}
		}
		
		private function blockAlreadyExists($selected_block_id, $current_page_blocks) {
			foreach ($current_page_blocks as $current_page_block) {
				if ($current_page_block->getId() == $selected_block_id) {
					return true;
				}
			}
			return false;
		}
		
		private function deleteSelectedBlocksFromPage() {	
			$current_page_blocks = $this->_current_page->getBlocks();
			foreach ($current_page_blocks as $current_page_block) {
				if ($this->isBlockSelectedForDeletion($current_page_block)) {
					$this->_current_page->deleteBlock($current_page_block);
				}
			}
		}
		
		private function isBlockSelectedForDeletion($current_page_block) {
			return isset($_POST["block_" . $this->_current_page->getId() . "_" . $current_page_block->getId() . "_delete"]);
		}
		
		private function deletePage() {
			$this->_page_dao->deletePage($this->_current_page);
            $parent = $this->_current_page->getParent();
			$current_level_pages = $parent->getSubPages();
			$this->updateFollowUp($current_level_pages);
			Notifications::setSuccessMessage("Pagina succesvol verwijderd");
			header("Location: /admin/index.php?page=1");
			exit();
		}
		
		private function addSubPage() {
			$new_page = new Page();
			$new_page->setParentId($this->_current_page->getId());
			$new_page->setShowInNavigation(true);
			$new_page->setNavigationTitle("Nieuwe pagina");
			$new_page->setTitle("Nieuwe pagina");
			$authorization_dao = AuthorizationDao::getInstance();
			$user = $authorization_dao->getUser($_SESSION["username"]);
			$new_page->setCreatedById($user->getId());
			$new_page->setType(ELEMENT_HOLDER_PAGE);
			$this->_page_dao->persist($new_page);
			
			$parent = $this->_page_dao->getPage($this->_current_page->getId());
			$current_level_pages = $parent->getSubPages();
			$this->updateFollowUp($current_level_pages);
			
			Notifications::setSuccessMessage("Pagina succesvol aangemaakt");
			header("Location: /admin/index.php?page=" . $new_page->getId());
			exit();
		}
		
		private function moveUp() {
			$this->_current_page->moveUp();
		}
		
		private function moveDown() {
			$this->_current_page->moveDown();
		}

		private function updateFollowUp($pages) {
			for ($i = 0; $i < count($pages); $i++) {
				$pages[$i]->setFollowUp($i);
				$this->_page_dao->updatePage($pages[$i]);
			}
		}
		
		private function getPageFromPostRequest() {
			return $this->_page_dao->getPage($_POST[self::$PAGE_ID_POST]);
		}
		
		private function getPageFromGetRequest() {
			if (isset($_GET[self::$PAGE_ID_GET]))
				return $this->_page_dao->getPage($_GET[self::$PAGE_ID_GET]);
			else
				return $this->_page_dao->getPage(self::$FALLBACK_PAGE_ID);
		}
		
		private function isUpdatePageAction() {
			return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
		}
		
		private function isDeletePageAction() {
			return isset($_POST["action"]) && $_POST["action"] == "delete_page";
		}
		
		private function isAddSubPageAction() {
			return isset($_POST["action"]) && $_POST["action"] == "sub_page";
		}
		
		private function isMoveUpAction() {
			return isset($_POST["action"]) && $_POST["action"] == "move_up";
		}
		
		private function isMoveDownAction() {
			return isset($_POST["action"]) && $_POST["action"] == "move_down";
		}

	}
?>