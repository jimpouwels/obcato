<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/model/settings.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "frontend/website_visual.php";
    require_once CMS_ROOT . 'utilities/url_helper.php';
    require_once CMS_ROOT . 'utilities/arrays.php';
    require_once CMS_ROOT . 'view/views/visual.php';
    require_once CMS_ROOT . 'view/views/panel.php';
    require_once CMS_ROOT . 'view/template_engine.php';
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';
    require_once CMS_ROOT . 'frontend/handlers/form_request_handler.php';

    class RequestHandler {

        private FormRequestHandler $_form_request_handler;
        private PageDao $_page_dao;
        private ArticleDao $_article_dao;
        private ImageDao $_image_dao;
        private SettingsDao $_settings_dao;
        private FriendlyUrlManager $_friendly_url_manager;

        public function __construct() {
            $this->_settings_dao = SettingsDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_image_dao = ImageDao::getInstance();
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
            $this->_form_request_handler = FormRequestHandler::getInstance();
        }

        public function handleRequest(): void {
            if ($this->isImageRequest()) {
                $this->loadImage();
            } else {
                $page = $this->getPageFromRequest();
                $article = $this->getArticleFromRequest();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->_form_request_handler->handlePost($page, $article);
                }
                if ($page) {
                    $this->renderPage($page, $article);
                } else {
                    $this->renderHomepage();
                }
            }
        }

        private function renderHomepage(): void {
            $homePage = $this->_settings_dao->getSettings()->getHomepage();
            $this->renderPage($homePage, null);
        }

        private function renderPage(Page $page, ?Article $article): void {
            $website = new WebsiteVisual($page, $article);
            echo $website->render();
        }

        private function loadImage(): void {
            $image = $this->getImageFromRequest();
            if ($image->isPublished()) {
                if ($image->getExtension() == "jpg") {
                    header("Content-Type: image/jpeg");
                } else if ($image->getExtension() == "gif") {
                    header("Content-Type: image/gif");
                } else if ($image->getExtension() == "png") {
                    header("Content-Type: img/png");
                } else {
                    header("Content-Type: image/" . $image->getExtension());
                }
                readfile(UPLOAD_DIR . "/" . $image->getFileName());
            }
        }

        private function getPageFromRequest(): ?Page {
            $page = $this->_friendly_url_manager->getPageFromUrl($_SERVER['REQUEST_URI']);
            if ($page == null && isset($_GET['id'])) {
                return $this->_page_dao->getPage($_GET['id']);
            } else {
                return $page;
            }
        }

        private function getArticleFromRequest(): ?Article {
            $article = $this->_friendly_url_manager->getArticleFromUrl($_SERVER['REQUEST_URI']);
            if ($article == null && isset($_GET['articleid'])) {
                return $this->_article_dao->getArticle($_GET['articleid']);
            } else {
                return $article;
            }
        }

        private function getImageFromRequest(): Image {
            return $this->_image_dao->getImage($_GET["image"]);
        }

        private function isImageRequest(): bool {
            return isset($_GET["image"]);
        }

    }

?>
