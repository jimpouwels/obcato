<?php

	// No direct access
	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "core/data/settings.php";
	require_once CMS_ROOT . "database/dao/page_dao.php";
	
	class RequestHandler {
		
		private $_page_dao;
	
		public function handleRequest() {
			$this->_page_dao = PageDao::getInstance();
			$page = $this->getPageFromRequest();
			$article = $this->getArticleFromRequest();
			$this->renderPage($page, $article);
		}
		
		private function renderPage($page, $article) {
			$website_settings = Settings::find();
					
			define("TEMPLATE_DIR", $website_settings->getFrontendTemplateDir());
			
			if (!is_null($page)) {
				include TEMPLATE_DIR . "/" . $page->getTemplate()->getFileName();
			}
		}
		
		private function getPageFromRequest() {					
			if (isset($_GET["id"]) && $_GET["id"] != "") {
				$page_id = $_GET["id"];
				$page = $this->_page_dao->getPage($page_id);
			} else {
				$settings_dao = SettingsDao::getInstance();
				$page = Settings::find()->getHomepage();
			}
			return $page;
		}
		
		private function getArticleFromRequest() {
			include_once CMS_ROOT . "database/dao/article_dao.php";
			
			$article_dao = ArticleDao::getInstance();
			
			$article = NULL;
			if (isset($_GET["articleid"]) && $_GET["articleid"] != "") {
				$article = $article_dao->getArticle($_GET["articleid"]);
			}
			
			return $article;
		}
	
	}
	
?>