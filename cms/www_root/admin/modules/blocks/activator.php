<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "visual/module_visual.php";
	require_once "visual/action_button.php";
	require_once "visual/tab_menu.php";
	require_once "dao/block_dao.php";
	require_once "modules/blocks/visuals/blocks/block_manager.php";
	require_once "modules/blocks/visuals/positions/position_manager.php";
	require_once "modules/blocks/block_pre_handler.php";

	class BlockModuleVisual extends ModuleVisual {
	
		private static $TEMPLATE = "blocks/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "blocks/head_includes.tpl";
		private static $BLOCK_QUERYSTRING_KEY = "block";
		private static $POSITION_QUERYSTRING_KEY = "position";
		private static $BLOCKS_TAB = 0;
		private static $POSITIONS_TAB = 1;
		
		private $_current_block;
		private $_current_position;
		private $_block_dao;
		private $_template_engine;
		private $_block_module;
		private $_block_pre_handler;
	
		public function __construct($block_module) {
			$this->_block_module = $block_module;
			$this->_block_pre_handler = new BlockPreHandler();
			$this->_block_dao = BlockDao::getInstance();
			$this->_template_engine = TemplateEngine::getInstance();
			$this->initialize();
		}
	
		public function render() {
			$this->_template_engine->assign("tab_menu", $this->renderTabMenu());
			
			$content = null;
			if ($this->_block_pre_handler->getCurrentTabId() == self::$BLOCKS_TAB) {
				$content = new BlockManager($this->_current_block);
			} else if ($this->_block_pre_handler->getCurrentTabId() == self::$POSITIONS_TAB) {
				$content = new PositionManager($this->_current_position);
			}
			
			if (!is_null($content)) {
				$this->_template_engine->assign("content", $content->render());
			}
			
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		public function getTitle() {
			return $this->_block_module->getTitle();
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			if ($this->_block_pre_handler->getCurrentTabId() == self::$BLOCKS_TAB) {
				if (!is_null($this->_current_block)) {
					$action_buttons[] = new ActionButton("Opslaan", "update_element_holder", "icon_apply");
					$action_buttons[] = new ActionButton("Verwijderen", "delete_element_holder", "icon_delete");
				}
				$action_buttons[] = new ActionButton("Toevoegen", "add_element_holder", "icon_add");
			}
			if ($this->_block_pre_handler->getCurrentTabId() == self::$POSITIONS_TAB) {
				if (!is_null($this->_current_position) || PositionManager::isEditPositionMode()) {
					$action_buttons[] = new ActionButton("Opslaan", "update_position", "icon_apply");
				}
				$action_buttons[] = new ActionButton("Toevoegen", "add_position", "icon_add");
				$action_buttons[] = new ActionButton("Verwijderen", "delete_positions", "icon_delete");
			}
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->_block_module->getIdentifier());
			return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
			include_once "modules/blocks/pre_handler.php";
			$this->initialize();
		}
		
		private function initialize() {
			$this->_current_block = $this->getCurrentBlock();
			$this->_current_position = $this->getCurrentPosition();
		}
		
		private function getCurrentBlock() {
			$current_block = null;
			if (isset($_GET[self::$BLOCK_QUERYSTRING_KEY]) && $_GET[self::$BLOCK_QUERYSTRING_KEY] != "") {
				$current_block = $this->_block_dao->getBlock($_GET[self::$BLOCK_QUERYSTRING_KEY]);
			}
			return $current_block;
		}
		
		private function getCurrentPosition() {
			$current_position = null;
			if (isset($_GET[self::$POSITION_QUERYSTRING_KEY])) {
				$position_id = $_GET[self::$POSITION_QUERYSTRING_KEY];
				$current_position = $this->_block_dao->getBlockPosition($position_id);
			}
			return $current_position;
		}
		
		private function renderTabMenu() {
			$tab_items = array();
			$tab_items[self::$BLOCKS_TAB] = "Blokken";
			$tab_items[self::$POSITIONS_TAB] = "Posities";
			$tab_menu = new TabMenu($tab_items, $this->_block_pre_handler->getCurrentTabId());
			return $tab_menu->render();
		}
	
	}
	
?>