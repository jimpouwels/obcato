<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/module.php";
	require_once "core/visual/action_button.php";

	class TemplateModuleVisual extends Module {
	
		private static $HEAD_INCLUDES_TEMPLATE = "templates/head_includes.tpl";
		
		private $_template_module;
		private $_template_engine;
		
		public function __construct($template_module) {
			$this->_template_module = $template_module;
			$this->_template_engine = TemplateEngine::getInstance();
		}
	
		public function render() {
		}
	
		public function getActionButtons() {
			$action_buttons = array();
			$action_buttons[] = new ActionButton("Toevoegen", "add_template", "icon_add");
			$action_buttons[] = new ActionButton("Toevoegen", "delete_template", "icon_delete");
			return $action_buttons;
		}
		
		public function getHeadIncludes() {
			$this->_template_engine->assign("path", $this->_template_module->getIdentifier());
			return $this->_template_engine->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
		}
		
		public function preHandle() {
		}
	
	}
	
?>