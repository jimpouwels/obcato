<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once FRONTEND_REQUEST . "core/data/presentable.php";
	include_once FRONTEND_REQUEST . "database/dao/scope_dao.php";

	class ElementType extends Entity {
	
		private $_name;
		private $_class_name;
		private $_edit_presentation;
		private $_icon_url;
		private $_identifier;
		private $_domain_object;
		private $_destroy_script;
		private $_scope_id;
		private $_system_default;
		
		public function getName() {
			return $this->_name;
		}
		
		public function setName($name) {
			$this->_name = $name;
		}
		
		public function getClassName() {
			return $this->_class_name;
		}
		
		public function setClassName($class_name) {
			$this->_class_name = $class_name;
		}
		
		public function getEditPresentation() {
			return $this->_edit_presentation;
		}
		
		public function getEditPresentationAbsolute() {
			$base = "";
			if (!$this->_system_default) {
				$base = COMPONENT_DIR . "/";
			}
			return $base . "elements/" . $this->_identifier . "/" . $this->_edit_presentation;
		}
		
		public function getRootDirectory() {
			$path = FRONTEND_REQUEST;
			if (!$this->_system_default) {
				$path .= COMPONENT_DIR . "/";
			}
			$path .= "elements/" . $this->_identifier;
			return $path;
		}
		
		public function setEditPresentation($edit_presentation) {
			$this->_edit_presentation = $edit_presentation;
		}
		
		public function getIconUrl() {
			return $this->_icon_url;
		}
		
		public function setIconUrl($icon_url) {
			$this->_icon_url = $icon_url;
		}
		
		public function getIconUrlAbsolute() {
			if ($this->_system_default) {
				$path = DEFAULT_ELEMENT_ICON_URL . $this->_icon_url;
			} else {
				$path = STATIC_FILES_URL . "/elements/" . $this->_identifier . "/" . $this->_icon_url;
			}
			return $path;
		}
		
		public function getIdentifier() {
			return $this->_identifier;
		}
		
		public function setIdentifier($identifier) {
			$this->_identifier = $identifier;
		}
		
		public function getDomainObject() {
			return $this->_domain_object;
		}
		
		public function setDomainObject($domain_object) {
			$this->_domain_object = $domain_object;
		}
		
		public function getScope() {
			$dao = ScopeDao::getInstance();
			return $dao->getScope($this->_scope_id);
		}
		
		public function setScope($scope) {
			if (!is_null($scope)) {
				$this->_scope_id = $scope->getId();
			}
		}
		
		public function getScopeId() {
			return $this->_scope_id;
		}
		
		public function setScopeId($scope_id) {
			$this->_scope_id = $scope_id;
		}
		
		public function getSystemDefault() {
			return $this->_system_default;
		}
		
		public function setSystemDefault($system_default) {
			$this->_system_default = $system_default;
		}
		
		public function setDestroyScript($destroy_script) {
			$this->_destroy_script = $destroy_script;
		}
		
		public function getDestroyScript() {
			return $this->_destroy_script;
		}
		
		public static function constructFromRecord($record) {
			$element_type = new ElementType();
			$element_type->setId($record['id']);
			$element_type->setName($record['name']);
			$element_type->setClassName($record['classname']);
			$element_type->setEditPresentation($record['edit_presentation']);
			$element_type->setIconUrl($record['icon_url']);
			$element_type->setIdentifier($record['identifier']);
			$element_type->setDomainObject($record['domain_object']);
			$element_type->setScopeId($record['scope_id']);
			$element_type->setSystemDefault($record['system_default']);
			$element_type->setDestroyScript($record['destroy_script']);
			
			return $element_type;
		}
	
	}
	
?>