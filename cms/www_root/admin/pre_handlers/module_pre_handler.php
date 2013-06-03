<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "pre_handlers/pre_handler.php";
	
	class ModulePreHandler extends PreHandler {
			
		private $_module_dao;
		private $_callback;
		
		public function __construct($callback) {
			$this->_callback = $callback;
			$this->_module_dao = ModuleDao::getInstance();
		}
	
		public function handle() {
			$current_module = null;
			$current_module_tab = 0;
			
			if (isset($_GET['home'])) {
				unset($_SESSION['module_id']);
			} else if (isset($_GET['module_id'])) {
				$_SESSION['module_id'] = $_GET['module_id'];
				unset($_SESSION['module_tab']);
			}
			
			if (isset($_GET['module_tab'])) {
				$_SESSION['module_tab'] = $_GET['module_tab'];
			}
			
			if (isset($_SESSION['module_tab'])) {
				$current_module_tab = $_SESSION['module_tab'];
			}
			
			if (isset($_SESSION['module_id'])) {
				$current_module = $this->_module_dao->getModule($_SESSION['module_id']);
				$current_module->setCurrentTabId($current_module_tab);
			}
			$this->_callback->setCurrentModule($current_module);
		}
		
	}

?>