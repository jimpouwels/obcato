<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/module.php";
	require_once "core/data/page.php";
	require_once "core/visual/action_button.php";
	require_once "modules/pages/visuals/page_tree.php";
	require_once "modules/pages/visuals/page_editor.php";

	class PageModule extends Module {
	
		private static $PAGE_ID_QS_KEY = "page";
		private static $PAGE_MODULE_TEMPLATE = "modules/pages/module_pages.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "modules/pages/head_includes.tpl";
	
		private $_current_page;
		private $_template_engine;
	
		public function __construct() {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->initialize();
		}
	
		public function render() {
			$page_tree = new PageTree(Settings::find()->getHomepage(), $this->_current_page, $this->getIdentifier());
			$page_editor = new PageEditor($this->_current_page, $this->getIdentifier());
			
			$this->_template_engine->assign("tree", $page_tree->render());
			$this->_template_engine->assign("editor", $page_editor->render());
			return $this->_template_engine->fetch(self::$PAGE_MODULE_TEMPLATE);
		}
		
		public function getActionButtons() {
			$buttons = array();
			$buttons[] = new ActionButton("Opslaan", "update_element_holder", "icon_apply");
			if (!is_null($this->_current_page)) {
				if ($this->_current_page->getId() != 1) {
					$buttons[] = new ActionButton("Verwijderen", "delete_element_holder", "icon_delete");
				}
			}
			$buttons[] = new ActionButton("Toevoegen", "add_element_holder", "icon_add");
			if ($this->_current_page->getId() != 1) {
				if (!$this->_current_page->isFirst()) {
					$buttons[] = new ActionButton("Omhoog", "moveup_element_holder", "icon_moveup");
				}
				if (!$this->_current_page->isLast()) {
					$buttons[] = new ActionButton("Omlaag", "movedown_element_holder", "icon_movedown");
				}
			}
			
			return $buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->getIdentifier());
			
			$element_statics_values = array();			
			$element_statics = $this->_current_page->getElementStatics();
			if (count($element_statics) > 0) {
				foreach ($element_statics as $element_static) {
					$element_statics_values[] = $element_static->render();
				}
			}
			$this->_template_engine->assign("element_statics", $element_statics_values);
			
			return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			include_once "modules/pages/pre_handler.php";
			$this->initialize();
		}
		
		private function initialize() {
			$this->_current_page = $this->getCurrentPage();
		}
		
		private function getCurrentPage() {
			$current_page = NULL;
			if (isset($_GET[self::$PAGE_ID_QS_KEY])) {
				$current_page = Page::findById($_GET[self::$PAGE_ID_QS_KEY]);
			} else {
				$current_page = Page::findById(1);
			}
			return $current_page;
		}
	
	}
	
?>