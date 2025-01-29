<?php

namespace Obcato\Core\frontend\handlers;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\dao\SettingsDao;
use Obcato\Core\database\dao\SettingsDaoMysql;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\frontend\cache\Cache;
use Obcato\Core\frontend\RobotsVisual;
use Obcato\Core\frontend\SitemapVisual;
use Obcato\Core\frontend\WebsiteVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use const Obcato\Core\UPLOAD_DIR;

class RequestHandler {

    private FormRequestHandler $formRequestHandler;
    private ImageDao $imageDao;
    private SettingsDao $settingsDao;
    private FriendlyUrlManager $friendlyUrlManager;
    private PageService $pageService;
    private Cache $cache;

    public function __construct() {
        $this->settingsDao = SettingsDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->formRequestHandler = FormRequestHandler::getInstance();
        $this->pageService = PageInteractor::getInstance();
        $this->cache = Cache::getInstance();
    }

    public function handleRequest(): void {
        if (str_contains($_SERVER['HTTP_HOST'], "www.www")) {
            $this->render404Page();
        }
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
                    $this->renderPage($urlMatch->getPage(), $urlMatch->getArticle(), $urlMatch->getOriginalUrl());
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
        if (!$image) {
            $this->render404Page();
        }
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

    private function getImageFromRequest(): ?Image {
        return $this->imageDao->getImage($_GET["image"]);
    }

    private function renderHomepage(): void {
        $homePage = $this->pageService->getHomepage();
        $this->renderPage($homePage, null, "");
    }

    private function renderPage(Page $page, ?Article $article, string $originalUrl): void {
        if ($article && $article->getTargetPageId() != $page->getId()) {
            $pageUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($this->pageService->getPageById($article->getTargetPageId()));
            $articleUrl = $this->friendlyUrlManager->getFriendlyUrlForElementHolder($article);
            header("Location: $pageUrl$articleUrl");
            exit();
        }
        $website = new WebsiteVisual($page, $article);
        $html = $website->render();
        $this->cache->insert($originalUrl, $html);
        echo $html;
    }

    private function render404Page(): void {
        $page404 = $this->settingsDao->getSettings()->getPage404();
        http_response_code(404);
        $this->renderPage($page404, null);
        exit();
    }

}
