<?php
	// No direct access
	defined('_ACCESS') or die;
		
	require_once "database/dao/page_dao.php";
	require_once "database/dao/block_dao.php";
	require_once "database/dao/element_dao.php";
	require_once "libraries/validators/form_validator.php";
	require_once "libraries/handlers/form_handler.php";
	require_once "libraries/system/notifications.php";
	require_once "view/request_handlers/module_request_handler.php";
	
	class PagePreHandler extends ModuleRequestHandler {
	
		private static $PAGE_ID_POST = "element_holder_id";
		private static $PAGE_ID_GET = "page";
		private static $FALLBACK_PAGE_ID = 1;
	
		private $_current_page;
		private $_page_dao;
		private $_block_dao;
		private $_element_dao;
		
		public function __construct() {
			$this->_page_dao = PageDao::getInstance();
			$this->_block_dao = BlockDao::getInstance();
			$this->_element_dao = ElementDao::getInstance();
		}
	
		public function handleGet() {
			$this->_current_page = $this->getPageFromGetRequest();
		}
		
		public function handlePost() {
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
			$this->assignFieldsWithObligations();
			
			if (!$this->getErrorCount()) {
				$this->assignOptionalFields();
				$this->updateElementOrder();
				$this->addSelectedBlocks();
				$this->deleteSelectedBlocksFromPage();
				$this->_current_page->update();
				
				Notifications::setSuccessMessage("Pagina succesvol opgeslagen");
			} else {
				Notifications::setFailedMessage("Pagina niet opgeslagen, verwerk de fouten");
			}
		}
		
		private function assignFieldsWithObligations() {
			$this->_current_page->setTitle(FormValidator::checkEmpty('page_title', 'Titel is verplicht'));
			$this->_current_page->setNavigationTitle(FormValidator::checkEmpty('navigation_title', 'Navigatietitel is verplicht'));
		}
		
		private function assignOptionalFields() {
			$this->_current_page->setDescription(FormHandler::getFieldValue("description"));
			$this->_current_page->setPublished($this->getPublishedValue());
			$this->_current_page->setShowInNavigation($this->getShowInNavigationValue());
			$this->_current_page->setTemplateId(FormHandler::getFieldValue("page_template"));
			echo $this->_current_page->getTemplateId();
		}
		
		private function getPublishedValue() {
			$published = FormHandler::getFieldValue('published');
			return $published == "on" ? 1 : 0;
		}
		
		private function getShowInNavigationValue() {
			$show_in_navigation = FormHandler::getFieldValue('show_in_navigation');
			return $show_in_navigation == "on" ? 1 : 0;
		}
		
		private function updateElementOrder() {
			$element_order = FormHandler::getFieldValue("element_order");
			$this->_element_dao->updateElementOrder($element_order, $this->_current_page);
		}
		
		private function addSelectedBlockToPage($selected_block_id, $current_page_blocks) {
			$selected_block = $this->_block_dao->getBlock($selected_block_id);
			if ($selected_block_id != -1 && (is_null($current_page_blocks) || count($current_page_blocks) == 0) || !in_array($selected_block, $current_page_blocks)) {
				$this->_current_page->addBlock($selected_block);
			}
		}
		
		private function addSelectedBlocks() {
			$selected_blocks = FormHandler::getFieldValue("select_blocks_" . $this->_current_page->getId());
			$current_page_blocks = $this->_current_page->getBlocks();
			if (!is_null($selected_blocks) && count($selected_blocks) > 0) {
				foreach ($selected_blocks as $selected_block_id) {
					$this->addSelectedBlockToPage($selected_block_id, $current_page_blocks);
				}
			}
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
			$parent = $this->_current_page->getParent();
			$this->_current_page->delete();
			$current_level_pages = $parent->getSubPages();
			$this->updateFollowUp($current_level_pages);
			Notifications::setSuccessMessage("Pagina succesvol verwijderd");
			header('Location: /admin/index.php?page=1');
			exit();
		}
		
		private function addSubPage() {
			$new_page = new Page();
			$new_page->setParentId($this->_current_page->getId());
			$new_page->persist();
			
			$parent = $this->_page_dao->getPage($this->_current_page->getId());
			$current_level_pages = $parent->getSubPages();
			$this->updateFollowUp($current_level_pages);
			
			Notifications::setSuccessMessage("Pagina succesvol aangemaakt");
			header('Location: /admin/index.php?page=' . $new_page->getId());
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
				$pages[$i]->update();
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