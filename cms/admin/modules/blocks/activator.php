<?php

	
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/module_visual.php";
	require_once CMS_ROOT . "view/views/tab_menu.php";
	require_once CMS_ROOT . "database/dao/block_dao.php";
	require_once CMS_ROOT . "modules/blocks/visuals/blocks/block_tab.php";
	require_once CMS_ROOT . "modules/blocks/visuals/positions/position_tab.php";
	require_once CMS_ROOT . "modules/blocks/block_pre_handler.php";
	require_once CMS_ROOT . "modules/blocks/position_pre_handler.php";

	class BlockModuleVisual extends ModuleVisual {
	
		private static $TEMPLATE = "blocks/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "blocks/head_includes.tpl";
		private static $BLOCKS_TAB = 0;
		private static $POSITIONS_TAB = 1;
		
		private $_current_block;
		private $_current_position;
		private $_block_dao;
		private $_template_engine;
		private $_block_module;
		private $_block_pre_handler;
		private $_position_pre_handler;
	
		public function __construct($block_module) {
			$this->_block_module = $block_module;
			$this->_block_pre_handler = new BlockPreHandler();
			$this->_position_pre_handler = new PositionPreHandler();
			$this->_block_dao = BlockDao::getInstance();
			$this->_template_engine = TemplateEngine::getInstance();
		}
	
		public function render() {
			$this->_template_engine->assign("tab_menu", $this->renderTabMenu());
			$content = null;
			if ($this->_block_pre_handler->getCurrentTabId() == self::$BLOCKS_TAB) {
				$content = new BlockTab($this->_current_block);
			} else if ($this->_block_pre_handler->getCurrentTabId() == self::$POSITIONS_TAB) {
				$content = new PositionTab($this->_current_position);
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
				if (!is_null($this->_current_position) || PositionTab::isEditPositionMode()) {
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
		
		public function getRequestHandlers() {
			$pre_handlers = array();
			$pre_handlers[] = $this->_block_pre_handler;
			$pre_handlers[] = $this->_position_pre_handler;
			return $pre_handlers;
		}
		
		public function onPreHandled() {
			$this->_current_block = $this->_block_pre_handler->getCurrentBlock();
			$this->_current_position = $this->_position_pre_handler->getCurrentPosition();
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