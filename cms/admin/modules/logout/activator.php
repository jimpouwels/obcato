<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "view/views/module_visual.php";
	require_once CMS_ROOT . "modules/logout/logout_pre_handler.php";

	class LogoutModuleVisual extends ModuleVisual {
	
		private $_module;
		private $_logout_pre_handler;
	
		public function __construct($module) {
			$this->_module = $module;
			$this->_logout_pre_handler = new LogoutPreHandler();
		}
	
		public function render() {
		}
	
		public function getActionButtons() {
		}
		
		public function getHeadIncludes() {
		}
		
		public function getPreHandlers() {
			$pre_handlers = array();
			$pre_handlers[] = $this->_logout_pre_handler;
			return $pre_handlers;
		}
		
		public function onPreHandled() {
		}
		
		public function getTitle() {
			return $this->_module->getTitle();
		}
	
	}
	
?>