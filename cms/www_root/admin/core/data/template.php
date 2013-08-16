<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once FRONTEND_REQUEST . "core/data/entity.php";
	require_once FRONTEND_REQUEST . "database/dao/scope_dao.php";

	class Template extends Entity {
	
		private $_file_name;
		private $_scope;
		private $_name;
		private $_scope_id;
		
		public function setName($name) {
			$this->_name = $name;
		}
		
		public function getName() {
			return $this->_name;
		}
		
		public function setFileName($file_name) {
			$this->_file_name = $file_name;
		}
		
		public function getFileName() {
			return $this->_file_name;
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
		
		public function exists() {
			$template_dir = Settings::find()->getFrontendTemplateDir();
			return file_exists($template_dir . "/" . $this->getFileName()) && $this->getFileName() != "";
		}
		
		public static function constructFromRecord($record) {
			$template = new Template();
			$template->setId($record['id']);
			$template->setFileName($record['filename']);
			$template->setName($record['name']);
			$template->setScopeId($record['scope_id']);
			
			return $template;
		}
	
	}
	
?>