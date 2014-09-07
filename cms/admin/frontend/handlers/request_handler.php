<?php

	// No direct access
	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "/core/data/settings.php";
	require_once CMS_ROOT . "/database/dao/page_dao.php";
    require_once CMS_ROOT . "/database/dao/article_dao.php";
    require_once CMS_ROOT . "/database/dao/settings_dao.php";
    require_once CMS_ROOT . "/frontend/page_visual.php";
	
	class RequestHandler {
		
		private $_page_dao;
        private $_article_dao;
        private $_settings_dao;

        public function __construct() {
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            DEFINE("TEMPLATE_DIR", SettingsDao::getInstance()->getSettings()->getFrontEndTemplateDir() . "/");
        }

		public function handleRequest() {
			$page = $this->getPageFromRequest();
			$article = $this->getArticleFromRequest();
			$this->renderPage($page, $article);
		}
		
		private function renderPage($page, $article) {
			if (!is_null($page)) {
                $page_visual = new PageVisual($page);
                $page_visual->render();
            }
		}
		
		private function getPageFromRequest() {
			if (isset($_GET["id"]) && $_GET["id"] != "") {
				$page_id = $_GET["id"];
				$page = $this->_page_dao->getPage($page_id);
			} else {
				$page = $this->_settings_dao->getSettings()->getHomepage();
			}
			return $page;
		}
		
		private function getArticleFromRequest() {
			$article = null;
			if (isset($_GET["articleid"]) && $_GET["articleid"] != "")
				$article = $this->_article_dao->getArticle($_GET["articleid"]);
			return $article;
		}
	
	}
	
?>