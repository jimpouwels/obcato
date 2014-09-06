<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/module_visual.php";

	class ComponentModuleVisual extends ModuleVisual {
		private $_component_module;
	
		public function __construct($component_module) {
			$this->_component_module = $component_module;
		}
	
		public function render() {
		}
	
		public function getActionButtons() {
		}
		
		public function getHeadIncludes() {
		}
		
		public function getPreHandlers() {
			$pre_handlers = array();
			return $pre_handlers;
		}
		
		public function onPreHandled() {
		}
		
		public function getTitle() {
			return $this->_component_module->getTitle();
		}
	
	}
	
?>