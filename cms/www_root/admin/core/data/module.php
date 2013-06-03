<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once "core/data/entity.php";
	include_once "libraries/system/template_engine.php";

	abstract class Module extends Entity {
	
		private static $TABLE_NAME = "modules";
	
		private $_id;
		private $_title;
		private $_icon_url;
		private $_identifier;
		private $_popup;
		private $_enabled;
		private $_is_system_default;
		private $_module_group_id;
		private $_current_tab_id;
		
		public function getTitle() {
			return $this->_title;
		}
		
		public function setTitle($title) {
			$this->_title = $title;
		}
		
		public function getIconUrl() {
			return $this->_icon_url;
		}
		
		public function setIconUrl($icon_url) {
			$this->_icon_url = $icon_url;
		}
		
		public function getIdentifier() {
			return $this->_identifier;
		}
		
		public function setIdentifier($identifier) {
			$this->_identifier = $identifier;
		}
		
		public function isPopUp() {
			return $this->_popup;
		}
		
		public function setPopUp($pop_up) {
			$this->_popup = $pop_up;
		}
		
		public function setEnabled($enabled) {
			$this->_enabled = $enabled;
		}
		
		public function isEnabled() {
			return $this->_enabled;
		}
		
		public function setSystemDefault($system_default) {
			$this->_is_system_default = $system_default;
		}
	
		public function isSystemDefault() {
			return $this->_is_system_default;
		}
		
		public function setModuleGroupId($module_group_id) {
			$this->_module_group_id = $module_group_id;
		}
	
		public function getModuleGroupId() {
			return $this->_module_group_id;
		}
		
		public function getModuleGroup() {
			include_once "dao/module_dao.php";
			$module_dao = ModuleDao::getInstance();
			$module_group = $module_dao->getModule($this->_module_group_id);
			return $module_group;
		}
		
		public function setCurrentTabId($current_tab_id) {
			$this->_current_tab_id = $current_tab_id;
		}
		
		public function getCurrentTabId() {
			return $this->_current_tab_id;
		}
		
		public function persist() {
		}
		
		public function update() {
		}
		
		public function delete() {
		}
		
		abstract function render();
		
		abstract function getActionButtons();
		
		abstract function getHeadIncludes();
		
		abstract function preHandle();
		
		public static function constructFromRecord($record) {
			$module = null;
		
			$class = $record['class'];
			$identifier = $record['identifier'];
			if (!is_null($class) && $class != '') {
				include_once "modules/$identifier/activator.php";
				$module = new $class();
				$module->setId($record['id']);
				$module->setTitle($record['title']);
				$module->setIconUrl($record['icon_url']);
				$module->setIdentifier($identifier);
				$module->setPopUp($record['popup']);
				$module->setEnabled($record['enabled']);
				$module->setSystemDefault($record['system_default']);
				$module->setModuleGroupId($record['module_group_id']);
			}
			return $module;
		}

	}
	
?>