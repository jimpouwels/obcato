<?php

	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "/view/form.php";
	require_once CMS_ROOT . "/database/dao/article_dao.php";
	
	class TermForm extends Form {
	
		private $_term;
		private $_article_dao;
	
		public function __construct($term) {
			$this->_term = $term;
			$this->_article_dao = ArticleDao::getInstance();
		}
		
		public function setTerm($term) {
			$this->_term = $term;
		}
	
		public function loadFields() {
			$this->_term->setName($this->getMandatoryFieldValue("name", "Naam is verplicht"));
			if ($this->hasErrors() || $this->termExists())
				throw new FormException();
		}
		
		private function termExists() {
			$existing_term = $this->_article_dao->getTermByName($this->_term->getName());
			if (!is_null($existing_term) && $this->_term->getId() != $existing_term->getId()) {
				$this->raiseError("name", "Er bestaat al een term met deze naam");
				return true;
			}
			return false;
		}
	
	}
	