<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/model/settings.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/image_dao.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/frontend/website_visual.php";
require_once CMS_ROOT . '/frontend/sitemap_visual.php';
require_once CMS_ROOT . '/utilities/url_helper.php';
require_once CMS_ROOT . '/utilities/arrays.php';
require_once CMS_ROOT . '/view/views/visual.php';
require_once CMS_ROOT . '/view/views/panel.php';
require_once CMS_ROOT . '/view/template_engine.php';
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';
require_once CMS_ROOT . '/frontend/handlers/form_request_handler.php';

class RequestHandler {

    private FormRequestHandler $_form_request_handler;
    private ImageDao $_image_dao;
    private SettingsDao $_settings_dao;
    private FriendlyUrlManager $_friendly_url_manager;

    public function __construct() {
        $this->_settings_dao = SettingsDaoMysql::getInstance();
        $this->_image_dao = ImageDao::getInstance();
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        $this->_form_request_handler = FormRequestHandler::getInstance();
    }

    public function handleRequest(): void {
        if ($this->isSitemapRequest()) {
            $sitemap = new SitemapVisual();
            header('Content-Type: application/xml');
            echo $sitemap->render();
        } else if ($this->isImageRequest()) {
            $this->loadImage();
        } else {
            $url = $_SERVER['REQUEST_URI'];
            $url_match = $this->_friendly_url_manager->matchUrl($url);
            if ($url == "/") {
                $this->renderHomepage();
            } else {
                if (!$url_match) {
                    $this->render404Page();
                } else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->_form_request_handler->handlePost($url_match->getPage(), $url_match->getArticle());
                    }
                    $this->renderPage($url_match->getPage(), $url_match->getArticle());
                }
            }
        }
    }

    private function isSitemapRequest(): bool {
        return isset($_GET['sitemap']) && $_GET['sitemap'];
    }

    private function isImageRequest(): bool {
        return isset($_GET["image"]);
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

    private function getImageFromRequest(): Image {
        return $this->_image_dao->getImage($_GET["image"]);
    }

    private function renderHomepage(): void {
        $homePage = $this->_settings_dao->getSettings()->getHomepage();
        $this->renderPage($homePage, null);
    }

    private function renderPage(Page $page, ?Article $article): void {
        $website = new WebsiteVisual($page, $article);
        echo $website->render();
    }

    private function render404Page(): void {
        $page_404 = $this->_settings_dao->getSettings()->get404Page();
        http_response_code(404);
        $this->renderPage($page_404, null);
        exit();
    }

}

?>
