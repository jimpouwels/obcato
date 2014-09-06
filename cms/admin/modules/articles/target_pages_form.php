<?php

	defined("_ACCESS") or die;
	
	require_once "pre_handlers/form.php";
	require_once "database/dao/article_dao.php";
	
	class TargetPagesForm extends Form {
	
		private $_target_page_to_add;
		private $_new_default_target_page;
		private $_target_pages_to_delete;
		private $_article_dao;
	
		public function __construct() {
			$this->_article_dao = ArticleDao::getInstance();
		}
	
		public function loadFields() {
			$this->_target_page_to_add = $this->getFieldValue("add_target_page_ref");
			$this->_new_default_target_page = $this->getFieldValue("new_default_target_page");
			$this->loadTargetPagesToDelete();
		}
		
		public function getTargetPageToAdd() {
			return $this->_target_page_to_add;
		}
		
		public function getNewDefaultTargetPage() {
			return $this->_new_default_target_page;
		}
		
		public function getTargetPagesToDelete() {
			return $this->_target_pages_to_delete;
		}
		
		private function loadTargetPagesToDelete() {
			$target_pages = $this->_article_dao->getTargetPages();
			foreach($target_pages as $target_page) {
				$field_to_check = "target_page_" . $target_page->getId() . "_delete";
				if (isset($_POST[$field_to_check]) && $_POST[$field_to_check] != "") {
					$this->_target_pages_to_delete[] = $target_page->getId();
				}
			}
		}
	
	}
	