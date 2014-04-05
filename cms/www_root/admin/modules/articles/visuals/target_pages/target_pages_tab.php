<?php
	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/template_engine.php";
	require_once FRONTEND_REQUEST . "view/views/page_picker.php";
	require_once FRONTEND_REQUEST . "database/dao/article_dao.php";
	
	class TargetPagesTab extends Visual {
	
		private static $TEMPLATE = "articles/target_pages/root.tpl";
	
		private $_template_engine;
		private $_article_dao;
	
		public function __construct() {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_article_dao = ArticleDao::getInstance();
		}
	
		public function render() {
			$this->_template_engine->assign("target_pages", $this->getTargetPages());
			$this->_template_engine->assign("default_target_page", $this->getDefaultTargetPage());
			
			$page_picker = new PagePicker("", null, "add_target_page_ref", "Doelpagina toevoegen", "update_target_pages", "articles");
			$this->_template_engine->assign("page_picker", $page_picker->render());
			
			return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
		}
		
		private function getDefaultTargetPage() {
			$target_page = $this->_article_dao->getDefaultTargetPage();
			$target_page_value = null;
			if (!is_null($target_page)) {
				$target_page_value = $this->toArray($target_page);
			}
			return $target_page_value;
		}
		
		private function getTargetPages() {
			$target_pages = array();
			foreach ($this->_article_dao->getTargetPages() as $target_page) {
				$target_pages[] = $this->toArray($target_page);
			}
			return $target_pages;
		}
		
		private function toArray($target_page) {
			$target_page_value = array();
			$target_page_value["id"] = $target_page->getId();
			$target_page_value["title"] = $target_page->getTitle();
			return $target_page_value;
		}
	}
	
?>