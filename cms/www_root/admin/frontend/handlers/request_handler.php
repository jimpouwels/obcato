<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "core/data/settings.php";
	require_once FRONTEND_REQUEST . "database/dao/page_dao.php";
	
	class RequestHandler {
		
		private $_page_dao;
	
		/*
			Handles the incoming frontend request.
		*/
		public function handleRequest() {
			$this->_page_dao = PageDao::getInstance();
			$page = $this->getPageFromRequest();
			$article = $this->getArticleFromRequest();
			$this->renderPage($page, $article);
		}
		
		/*
			Renders the page.
			
			@param $page The page to render
			@param $article The article to render
		*/
		private function renderPage($page, $article) {
			$website_settings = Settings::find();
					
			define("TEMPLATE_DIR", $website_settings->getFrontendTemplateDir());
			
			if (!is_null($page)) {
				include TEMPLATE_DIR . '/' . $page->getTemplate()->getFileName();
			}
		}
		
		/*
			Returns the requested page.
		*/
		private function getPageFromRequest() {					
			if (isset($_GET['id']) && $_GET['id'] != '') {
				$page_id = $_GET['id'];
				$page = $this->_page_dao->getPage($page_id);
			} else {
				$settings_dao = SettingsDao::getInstance();
				$page = Settings::find()->getHomepage();
			}
			return $page;
		}
		
		/*
			Returns the requested article.
		*/
		private function getArticleFromRequest() {
			include_once FRONTEND_REQUEST . "database/dao/article_dao.php";
			
			$article_dao = ArticleDao::getInstance();
			
			$article = NULL;
			if (isset($_GET['articleid']) && $_GET['articleid'] != '') {
				$article = $article_dao->getArticle($_GET['articleid']);
			}
			
			return $article;
		}
	
	}
	
?>