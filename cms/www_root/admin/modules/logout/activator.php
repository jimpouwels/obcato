<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "view/views/module_visual.php";
	require_once "modules/logout/logout_pre_handler.php";

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
		
		public function preHandle() {
			$this->_logout_pre_handler->handle();
		}
		
		public function getTitle() {
			return $this->_module->getTitle();
		}
	
	}
	
?>