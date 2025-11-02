<?php

namespace Obcato\Core\frontend\handlers;

use JetBrains\PhpStorm\NoReturn;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\dao\SettingsDao;
use Obcato\Core\database\dao\SettingsDaoMysql;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\frontend\cache\Cache;
use Obcato\Core\frontend\helper\FrontendHelper;
use Obcato\Core\frontend\RobotsVisual;
use Obcato\Core\frontend\SitemapVisual;
use Obcato\Core\frontend\WebsiteVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\settings\model\Settings;
use Obcato\Core\rest\Router;
use Obcato\Core\utilities\ImageUtility;
use Obcato\Core\utilities\UrlHelper;
use const Obcato\Core\UPLOAD_DIR;

class RequestHandler {

    private FormRequestHandler $formRequestHandler;
    private ImageDao $imageDao;
    private SettingsDao $settingsDao;
    private FriendlyUrlManager $friendlyUrlManager;
    private PageService $pageService;
    private Cache $cache;
    private Settings $settings;

    public function __construct() {
        $this->settingsDao = SettingsDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
        $this->formRequestHandler = FormRequestHandler::getInstance();
        $this->pageService = PageInteractor::getInstance();
        $this->cache = Cache::getInstance();
        $this->settings = $this->settingsDao->getSettings();
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
        } else if ($this->isRestRequest()) {
            $router = new Router();
            $router->route();
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

    private function isRestRequest(): bool {
        return isset($_GET['rest']) && $_GET['rest'] == "true";
    }

    private function isImageRequest(): bool {
        return str_starts_with($_SERVER['REQUEST_URI'], '/image/');
    }

    private function loadImage(): void {
        $image = $this->getImageFromRequest();
        if (!$image) {
            $this->render404Page();
        }
        if (isset($_GET["mobile"]) && $_GET["mobile"] == "true") {
            if (!ImageUtility::exists($image->getMobileFilename())) {
                $mobileImage = ImageUtility::loadImage($image->getFilename());
                ImageUtility::saveImageAsWebp(ImageUtility::scaleX($mobileImage, 768), $image->getMobileFilename());
            }
            $imageFilepath = UPLOAD_DIR . "/" . $image->getMobileFilename();
        } else {
            $imageFilepath = UPLOAD_DIR . "/" . $image->getFilename();
        }
        if ($image->isPublished()) {
            header("Content-Type: image/webp");
            header("Cache-Control: max-age=" . $this->settings->getBrowserImageCacheInSeconds() * 60 * 60);
            readfile($imageFilepath);
        }
    }

    private function getImageFromRequest(): ?Image {
        return $this->imageDao->getImage(intval(UrlHelper::splitIntoParts($_SERVER['REQUEST_URI'])[2]));
    }

    private function renderHomepage(): void {
        $homePage = $this->pageService->getHomepage();
        $this->renderPage($homePage, null, "");
    }

    private function renderPage(Page $page, ?Article $article, string $originalUrl): void {
        if (!FrontendHelper::isPreviewMode() && ((!is_null($article) && !$article->isPublished()) || !$page->isPublished())) {
            $this->render404Page();
        }
        if ($article && $article->getTargetPageId() != $page->getId()) {
            $this->render404Page();
        }
        $website = new WebsiteVisual($page, $article);
        $html = $website->render();
        $this->cache->insert($originalUrl, $html);
        echo $html;
    }

    #[NoReturn]
    private function render404Page(): void {
        $page404 = $this->settings->getPage404();
        http_response_code(404);
        $this->renderPage($page404, null, "404");
        exit();
    }

}
