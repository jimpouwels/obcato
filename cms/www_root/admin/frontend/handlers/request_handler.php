<?php

	// No direct access
	defined('_ACCESS') or die;
	
	class RequestHandler {
		
		/*
			Handles the incoming frontend request.
		*/
		public function handleRequest() {
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
				$page = Page::findById($page_id);
			} else {
				include_once FRONTEND_REQUEST . "database/dao/settings_dao.php";
				$settings_dao = SettingsDao::getInstance();
				$page = $settings_dao->getHomepage();
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