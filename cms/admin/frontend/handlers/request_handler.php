<?php

	
	defined("_ACCESS") or die;
	
	require_once CMS_ROOT . "core/data/settings.php";
	require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "frontend/page_visual.php";
	
	class RequestHandler {

        const REQUEST_OBJECT_PAGE = "PAGE";
        const REQUEST_OBJECT_IMAGE = "IMAGE";

		private $_page_dao;
        private $_article_dao;
        private $_image_dao;
        private $_settings_dao;

        public function __construct() {
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_image_dao = ImageDao::getInstance();
        }

		public function handleRequest() {
            switch ($this->getRequestedObject()) {
                case self::REQUEST_OBJECT_PAGE:
                    $this->renderPage($this->getPageFromRequest(), $this->getArticleFromRequest());
                    break;
                case self::REQUEST_OBJECT_IMAGE:
                    $this->loadImage();
                    break;
                default:
                    $this->renderHomepage();
            }
		}

        private function renderHomepage() {
            $homePage = $this->_settings_dao->getSettings()->getHomepage();
            if ($homePage->isPublished())
                $this->renderPage($homePage, null);
        }
		
		private function renderPage($page, $article) {
			if (!is_null($page) && $page->isPublished()) {
                $page_visual = new PageVisual($page, $article);
                $page_visual->render();
            }
		}

        private function loadImage() {
            $image = $this->getImageFromRequest();
            if ($image->isPublished()) {
                if ($image->getExtension() == "jpg")
                    header("Content-Type: image/jpeg");
                else if ($image->getExtension() == "gif")
                    header("Content-Type: image/gif");
                else if ($image->getExtension() == "png")
                    header("Content-Type: img/png");
                else
                    header("Content-Type: image/" . $image->getExtension());
                readfile(UPLOAD_DIR . "/" . $image->getFileName());
            }
        }
		
		private function getPageFromRequest() {
			return $this->_page_dao->getPage($_GET["id"]);
		}

        private function getImageFromRequest() {
            return $this->_image_dao->getImage($_GET["image"]);
        }
		
		private function getArticleFromRequest() {
			$article = null;
			if (isset($_GET["articleid"]) && $_GET["articleid"] != "")
				$article = $this->_article_dao->getArticle($_GET["articleid"]);
			return $article;
		}

        private function getRequestedObject() {
            if (isset($_GET["id"]))
                return self::REQUEST_OBJECT_PAGE;
            else if (isset($_GET["image"]))
                return self::REQUEST_OBJECT_IMAGE;
        }
	
	}
	
?>