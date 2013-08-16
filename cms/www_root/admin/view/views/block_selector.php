<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "view/template_engine.php";
	require_once "view/views/form_checkbox_single.php";
	require_once "database/dao/block_dao.php";
	
	class BlockSelector extends Visual {
	
		private static $TEMPLATE = "system/block_selector.tpl";
		private $_template_engine;
		private $_selected_blocks;
		private $_block_dao;
		private $_context_id;
		
		public function __construct($selected_blocks, $context_id) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_block_dao = BlockDao::getInstance();
			$this->_context_id = $context_id;
			$this->_selected_blocks = $selected_blocks;
		}
		
		public function render() {
			$this->_template_engine->assign("blocks_to_select", $this->getBlocksToSelect());
			$this->_template_engine->assign("selected_blocks", $this->getSelectedBlocksHtml());
			$this->_template_engine->assign("context_id", $this->_context_id);
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
		
		private function getBlocksToSelect() {
			$blocks_to_select = array();
			
			foreach ($this->_block_dao->getAllBlocks() as $block) {
				if (!in_array($block, $this->_selected_blocks)) {
					$block_to_select["id"] = $block->getId();
					$block_to_select["title"] = $block->getTitle();
					$blocks_to_select[] = $block_to_select;
				}
			}
			return $blocks_to_select;
		}
		
		private function getSelectedBlocksHtml() {
			$selected_blocks = array();
			if (count($this->_selected_blocks) > 0) {
				foreach ($this->_selected_blocks as $selected_block) {
					$selected_block_item = array();
					$selected_block_item["title"] = $selected_block->getTitle();
					$selected_block_item["position_name"] = $selected_block->getPositionName();
					$selected_block_item["published"] = $selected_block->isPublished();
					
					$delete_field = new SingleCheckbox("block_" . $this->_context_id . "_" . $selected_block->getId() . "_delete", "", false, false, "");
					$selected_block_item["delete_field"] = $delete_field->render();
					$selected_blocks[] = $selected_block_item;
				}
			}
			return $selected_blocks;
		}
		
	}

?>