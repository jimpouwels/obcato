<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/module.php";
	require_once "view/views/action_button.php";
	require_once "modules/templates/template_pre_handler.php";
	require_once "modules/templates/visuals/template_list.php";

	class TemplateModuleVisual extends Module {
	
		private static $TEMPLATE_MODULE_TEMPLATE = "modules/templates/root.tpl";
		private static $HEAD_INCLUDES_TEMPLATE = "templates/head_includes.tpl";
		
		private $_template_module;
		private $_template_engine;
		private $_template_pre_handler;
		private $_current_template;
		
		public function __construct($template_module) {
			$this->_template_module = $template_module;
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_template_pre_handler = new TemplatePreHandler();
		}

		public function render() {
			$this->_template_engine->assign("current_template_id", $this->getCurrentTemplateId());
			if (!is_null($this->_current_template))
				$this->_template_engine->assign("template_editor", $this->renderTemplateEditor());
			else
				$this->_template_engine->assign("template_list", $this->renderTemplateList());
			return $this->_template_engine->fetch(self::$TEMPLATE_MODULE_TEMPLATE);
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
			$this->_template_pre_handler->handle();
			$this->_current_template = $this->_template_pre_handler->getCurrentTemplate();
		}
		
		private function renderTemplateEditor() {
			
		}
		
		private function renderTemplateList() {
			$template_list = new TemplateList();
			return $template_list->render();
		}
		
		private function getCurrentTemplateId() {
			$current_template_id = null;
			if (!is_null($this->_current_template)) {
				$current_template_id = $this->_current_template->getId();
			}
			return $current_template_id;
		}
	
	}
	
?>