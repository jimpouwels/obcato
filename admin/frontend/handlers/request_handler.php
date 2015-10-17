<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/settings.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "frontend/page_visual.php";
    require_once CMS_ROOT . 'frontend/friendly_urls/friendly_url_manager.php';

    class RequestHandler {

        private $_page_dao;
        private $_article_dao;
        private $_image_dao;
        private $_settings_dao;
        private $_friendly_url_manager;

        public function __construct() {
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_image_dao = ImageDao::getInstance();
            $this->_friendly_url_manager = new FriendlyUrlManager();
        }

        public function handleRequest() {
            if ($this->isImageRequest())
                $this->loadImage();
            else
                $this->renderPage($this->getPageFromRequest(), $this->getArticleFromRequest());
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
            $page = $this->_friendly_url_manager->getPageFromUrl(ltrim($_SERVER['REQUEST_URI'], '/'));
            if ($page == null)
                return $this->_page_dao->getPage($_GET["id"]);
            else
                return $page;
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

        private function isImageRequest() {
            return isset($_GET["image"]);
        }

    }

?>
