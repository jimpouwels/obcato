<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once FRONTEND_REQUEST . "database/dao/scope_dao.php";

	class ScopeSelector extends Visual {
		
		private static $SCOPE_SELECTOR = "templates/scope_selector.tpl";

		private $_template_dao;
		private $_scope_dao;
		private $_template_engine;
		
		public function __construct() {
			$this->_template_dao = TemplateDao::getInstance();
			$this->_scope_dao = ScopeDao::getInstance();
			$this->_template_engine = TemplateEngine::getInstance();
		}
		
		public function render() {
			$this->_template_engine->assign("scopes", $this->getAllScopes());
			return $this->_template_engine->fetch("modules/" . self::$SCOPE_SELECTOR);
		}
		
		private function getAllScopes() {
			$scopes = array();
			foreach ($this->_scope_dao->getScopes() as $scope) {
				$scope_data = array();
				$scopes[] = $scope->getName();
			}
			return $scopes;
		}
		
	}