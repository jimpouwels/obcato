<?php

namespace Obcato\Core\admin\frontend\handlers;

use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\database\dao\SettingsDao;
use Obcato\Core\admin\database\dao\SettingsDaoMysql;
use Obcato\Core\admin\friendly_urls\FriendlyUrlManager;
use Obcato\Core\admin\frontend\RobotsVisual;
use Obcato\Core\admin\frontend\SitemapVisual;
use Obcato\Core\admin\frontend\WebsiteVisual;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\images\model\Image;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\pages\service\PageInteractor;
use Obcato\Core\admin\modules\pages\service\PageService;
use const Obcato\Core\admin\UPLOAD_DIR;

class RequestHandler {

    private FormRequestHandler $formRequestHandler;
    private ImageDao $imageDao;
    private SettingsDao $settingsDao;
    private FriendlyUrlManager $friendlyUrlManager;
    private PageService $pageService;

    public function __construct() {
        $this->settingsDao = SettingsDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->formRequestHandler = FormRequestHandler::getInstance();
        $this->pageService = PageInteractor::getInstance();
    }

    public function handleRequest(): void {
        if ($this->isSitemapRequest()) {
            $sitemap = new SitemapVisual();
            header('Content-Type: application/xml');
            echo $sitemap->render();
        } else if ($this->isRobotsRequest()) {
            $robots = new RobotsVisual();
            header('Content-Type: text/plain');
            echo $robots->render();
        } else if ($this->isImageRequest()) {
            $this->loadImage();
        } else {
            $url = explode('?', $_SERVER['REQUEST_URI'])[0];
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

    private function isRobotsRequest(): bool {
        return isset($_GET['robots']) && $_GET['robots'];
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
            readfile(UPLOAD_DIR . "/" . $image->getFilename());
        }
    }

    private function getImageFromRequest(): Image {
        return $this->imageDao->getImage($_GET["image"]);
    }

    private function renderHomepage(): void {
        $homePage = $this->pageService->getHomepage();
        $this->renderPage($homePage, null);
    }

    private function renderPage(Page $page, ?Article $article): void {
        $website = new WebsiteVisual($page, $article);
        echo $website->render();
    }

    private function render404Page(): void {
        $page404 = $this->settingsDao->getSettings()->getPage404();
        http_response_code(404);
        $this->renderPage($page404, null);
        exit();
    }

}
