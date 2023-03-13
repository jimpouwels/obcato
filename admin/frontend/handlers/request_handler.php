<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/settings.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "frontend/page_visual.php";
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

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
            else {
                $page = $this->getPageFromRequest();
                if ($page) {
                    $this->renderPage($page, $this->getArticleFromRequest());
                }
                else
                    $this->renderHomepage();
            }
        }

        private function renderHomepage() {
            $homePage = $this->_settings_dao->getSettings()->getHomepage();
            $this->renderPage($homePage, null);
        }

        private function renderPage($page, $article) {
            if ($page->isPublished()) {
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
            $page = $this->_friendly_url_manager->getPageFromUrl($_SERVER['REQUEST_URI']);
            if ($page == null && isset($_GET['id']))
                return $this->_page_dao->getPage($_GET['id']);
            else
                return $page;
        }

        private function getArticleFromRequest() {
            $article = $this->_friendly_url_manager->getArticleFromUrl($_SERVER['REQUEST_URI']);
            if ($article == null && isset($_GET['articleid']))
                return $this->_article_dao->getArticle($_GET['articleid']);
            else
                return $article;
        }

        private function getImageFromRequest() {
            return $this->_image_dao->getImage($_GET["image"]);
        }

        private function isImageRequest() {
            return isset($_GET["image"]);
        }

    }

?>
