<?php
require_once CMS_ROOT . "/modules/settings/model/Settings.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/frontend/WebsiteVisual.php";
require_once CMS_ROOT . '/frontend/SitemapVisual.php';
require_once CMS_ROOT . '/utilities/UrlHelper.php';
require_once CMS_ROOT . '/utilities/Arrays.php';
require_once CMS_ROOT . '/view/views/Visual.php';
require_once CMS_ROOT . '/view/views/Panel.php';
require_once CMS_ROOT . '/view/TemplateEngine.php';
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';
require_once CMS_ROOT . '/frontend/handlers/FormRequestHandler.php';

class RequestHandler {

    private FormRequestHandler $formRequestHandler;
    private ImageDao $imageDao;
    private SettingsDao $settingsDao;
    private FriendlyUrlManager $friendlyUrlManager;

    public function __construct() {
        $this->settingsDao = SettingsDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->formRequestHandler = FormRequestHandler::getInstance();
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
            $urlMatch = $this->friendlyUrlManager->matchUrl($url);
            if ($url == "/") {
                $this->renderHomepage();
            } else {
                if (!$urlMatch) {
                    $this->render404Page();
                } else {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->formRequestHandler->handlePost($urlMatch->getPage(), $urlMatch->getArticle());
                    }
                    $this->renderPage($urlMatch->getPage(), $urlMatch->getArticle());
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
        return $this->imageDao->getImage($_GET["image"]);
    }

    private function renderHomepage(): void {
        $homePage = $this->settingsDao->getSettings()->getHomepage();
        $this->renderPage($homePage, null);
    }

    private function renderPage(Page $page, ?Article $article): void {
        $website = new WebsiteVisual($page, $article);
        echo $website->render();
    }

    private function render404Page(): void {
        $page404 = $this->settingsDao->getSettings()->get404Page();
        http_response_code(404);
        $this->renderPage($page404, null);
        exit();
    }

}

?>
