<?php

	// No direct access
	defined('_ACCESS') or die;

	include_once CMS_ROOT . "/core/data/entity.php";

	abstract class Presentable extends Entity {
		
		private $_template_id;
		private $_scope_id;
		
		public function getTemplate() {
			$dao = TemplateDao::getInstance();
			if ($this->_template_id)
				return $dao->getTemplate($this->_template_id);
			else
				return null;
		}
		
		public function setTemplate($template) {
			if (!is_null($template)) {
				$this->_template_id = $template->getId();
			}
		}
		
		public function getTemplateId() {
			return $this->_template_id;
		}
		
		public function setTemplateId($template_id) {
			$this->_template_id = $template_id;
		}
		
		public function getScope() {
			$dao = ScopeDao::getInstance();
			return $dao->getScope($this->_scope_id);
		}
		
		public function getScopeId() {
			return $this->_scope_id;
		}
		
		public function setScopeId($scope_id) {
			$this->_scope_id = $scope_id;
		}
	}
	
?>