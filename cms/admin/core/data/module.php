<?php

	
	defined('_ACCESS') or die;

	require_once CMS_ROOT . "core/data/entity.php";

	class Module extends Entity {

		private $_title;
		private $_icon_url;
		private $_identifier;
		private $_popup;
		private $_enabled;
		private $_is_system_default;
		private $_module_group_id;
		private $_class;
		
		public function getTitle() {
			return $this->_title;
		}
		
		public function setTitle($title) {
			$this->_title = $title;
		}
		
		public function getClass() {
			return $this->_class;
		}
		
		public function setClass($class) {
			$this->_class = $class;
		}
		
		public function getIconUrl() {
			return '/modules/' . $this->_identifier . $this->_icon_url;
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
			include_once CMS_ROOT . "database/dao/module_dao.php";
			$module_dao = ModuleDao::getInstance();
			$module_group = $module_dao->getModule($this->_module_group_id);
			return $module_group;
		}
		
		public static function constructFromRecord($record) {
			$module = null;
		
			$class = $record['class'];
			$identifier = $record['identifier'];
			if (!is_null($class) && $class != '') {
				$module = new Module();
				$module->setId($record['id']);
				$module->setTitle($record['title']);
				$module->setIconUrl($record['icon_url']);
				$module->setIdentifier($identifier);
				$module->setPopUp($record['popup']);
				$module->setEnabled($record['enabled']);
				$module->setSystemDefault($record['system_default']);
				$module->setModuleGroupId($record['module_group_id']);
				$module->setClass($record['class']);
			}
			return $module;
		}

	}
	
?>