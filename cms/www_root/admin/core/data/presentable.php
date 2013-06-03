<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once FRONTEND_REQUEST . "core/data/entity.php";

	abstract class Presentable extends Entity {
		
		private $myTemplateId;
		private $myScopeId;
		
		public function getTemplate() {
			$dao = TemplateDao::getInstance();
			return $dao->getTemplate($this->myTemplateId);
		}
		
		public function setTemplate($template) {
			if (!is_null($template)) {
				$this->myTemplateId = $template->getId();
			}
		}
		
		public function getTemplateId() {
			return $this->myTemplateId;
		}
		
		public function setTemplateId($template_id) {
			$this->myTemplateId = $template_id;
		}
		
		public function getScope() {
			$dao = ScopeDao::getInstance();
			return $dao->getScope($this->myScopeId);
		}
		
		public function setScope($scope) {
			if (!is_null($scope)) {
				$this->myScopeId = $scope->getId();
			}
		}
		
		public function getScopeId() {
			return $this->myScopeId;
		}
		
		public function setScopeId($scope_id) {
			$this->myScopeId = $scope_id;
		}
	}
	
?>