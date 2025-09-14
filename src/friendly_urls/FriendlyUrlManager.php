<?php

namespace Obcato\Core\friendly_urls;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\dao\FriendlyUrlDao;
use Obcato\Core\database\dao\FriendlyUrlDaoMysql;
use Obcato\Core\database\dao\PageDao;
use Obcato\Core\database\dao\PageDaoMysql;
use Obcato\Core\frontend\helper\FrontendHelper;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\utilities\UrlHelper;
use const Obcato\PUBLIC_DIR;

class FriendlyUrlManager {

    private static ?FriendlyUrlManager $instance = null;
    private FriendlyUrlDao $friendlyUrlDao;
    private PageDao $pageDao;
    private ArticleDao $articleDao;

    public function __construct(FriendlyUrlDao $friendlyUrlDao,
                                PageDao        $pageDao,
                                ArticleDao     $articleDao) {
        $this->friendlyUrlDao = $friendlyUrlDao;
        $this->pageDao = $pageDao;
        $this->articleDao = $articleDao;
        self::writeHtaccessFileIfNotExists();
    }

    public static function writeHtaccessFileIfNotExists(): void {
        $htaccessFilePath = PUBLIC_DIR . '/.htaccess';
        if (file_exists($htaccessFilePath)) return;
        $handle = fopen($htaccessFilePath, 'w');
        fclose($handle);
        file_put_contents($htaccessFilePath, "RewriteEngine on\n\n" .
            "RewriteCond %{HTTP_HOST} !=localhost\n" .
            "RewriteCond %{HTTPS} !=on\n" .
            "RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n" .
            "RewriteCond %{HTTP_HOST} !^www\.\n" .
            "RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n" .
            "RewriteCond %{REQUEST_URI} !^/index.php\n" .
            "RewriteRule ^sitemap.xml$ /index.php?sitemap=true [NC,L]\n" .
            "RewriteRule ^robots.txt$ /index.php?robots=true [NC,L]\n\n" .
            "RewriteCond %{REQUEST_URI} !\.(.*)$\n" .
            "RewriteRule ^.*$ index.php [NC,L]");
    }

    public static function getInstance(): FriendlyUrlManager {
        if (!self::$instance) {
            self::$instance = new FriendlyUrlManager(FriendlyUrlDaoMysql::getInstance(),
                PageDaoMysql::getInstance(),
                ArticleDaoMysql::getInstance());
        }
        return self::$instance;
    }

    public function insertOrUpdateFriendlyUrlForPage(Page $page): void {
        $url = $this->createUrlForPage($page);
        $this->insertOrUpdateFriendlyUrl($url, $page);
    }

    private function createUrlForPage(Page $page): string {
        $url = '/' . $this->replaceSpecialCharacters($page->getUrlTitle() ?: $page->getNavigationTitle());
        $parentPage = $this->pageDao->getParent($page);
        if ($parentPage != null && $parentPage->getId() != $this->pageDao->getRootPage()->getId() && $page->getIncludeParentInUrl()) {
            $url = $this->createUrlForPage($this->pageDao->getParent($page)) . $url;
        }
        return $url;
    }

    public function getFriendlyUrlForElementHolder(ElementHolder $elementHolder): ?string {
        return $this->friendlyUrlDao->getUrlFromElementHolder($elementHolder);
    }

    public function insertOrUpdateFriendlyUrlForArticle(Article $article): void {
        $url = $this->createUrlForArticle($article);
        $this->insertOrUpdateFriendlyUrl($url, $article);
    }

    public function matchUrl(string $url): ?UrlMatch {
        if (str_ends_with($url, '/')) {
            $url = rtrim($url, "/");
        }
        $urlMatch = new UrlMatch($url);
        $this->getPageFromUrl($url, $urlMatch);

        if (!$urlMatch->getPage()) {
            return null;
        }
        if (strlen($urlMatch->getPageUrl()) < strlen($url)) {
            $this->getArticleFromUrl($url, $urlMatch);
            if (!$urlMatch->getArticle()) {
                return null;
            }
        }
        return $urlMatch;
    }

    private function createUrlForArticle(Article $article): string {
        $base = $article->getParentArticleId() ? ($this->getFriendlyUrlForElementHolder($this->articleDao->getArticle($article->getParentArticleId())) . '/') : '/';
        return $base . $this->replaceSpecialCharacters($article->getUrlTitle() ?: $article->getTitle());
    }

    private function replaceSpecialCharacters(string $value): string {
        $value = strtolower($value);
        $value = str_replace(' - ', ' ', $value);
        $value = str_replace(' (', ' ', $value);
        $value = str_replace(')', '', $value);
        $value = str_replace('\'', '', $value);
        $value = str_replace('&', '', $value);
        $value = str_replace('  ', ' ', $value);
        $value = str_replace(' ', '-', $value);
        return urlencode($value);
    }

    private function insertOrUpdateFriendlyUrl(string $url, ElementHolder $elementHolder): void {
        $url = $this->appendNumberIfFriendlyUrlExists($url, $elementHolder);
        if (!$this->getFriendlyUrlForElementHolder($elementHolder)) {
            $this->friendlyUrlDao->insertFriendlyUrl($url, $elementHolder);
        } else {
            $this->friendlyUrlDao->updateFriendlyUrl($url, $elementHolder);
        }
    }

    private function appendNumberIfFriendlyUrlExists(string $url, ElementHolder $elementHolder): string {
        $newUrl = $url;
        $existingElementHolderId = $this->friendlyUrlDao->getElementHolderIdFromUrl($url);
        $number = 1;
        while ($existingElementHolderId != null && $existingElementHolderId != $elementHolder->getId()) {
            $newUrl = $url . $number;
            $number++;
            $existingElementHolderId = $this->friendlyUrlDao->getElementHolderIdFromUrl($newUrl);
        }
        return $newUrl;
    }

    private function getPageFromUrl(string $url, UrlMatch $urlMatch): void {
        $url = UrlHelper::removeQueryStringFrom($url);
        $elementHolderId = $this->friendlyUrlDao->getElementHolderIdFromUrl($url);
        $page = $this->pageDao->getPageByElementHolderId($elementHolderId);

        $matchedUrl = $url;
        if (is_null($page)) {
            $urlParts = UrlHelper::splitIntoParts($url);
            for ($i = 0; $i < count($urlParts); $i++) {
                $subArray = array_slice($urlParts, 1, (count($urlParts) - $i - 1));
                $pagePartOfUrl = '/' . implode('/', $subArray);
                $elementHolderId = $this->friendlyUrlDao->getElementHolderIdFromUrl($pagePartOfUrl);
                $page = $this->pageDao->getPageByElementHolderId($elementHolderId);
                if ($page) {
                    $matchedUrl = $pagePartOfUrl;
                    break;
                }
            }
        }
        $urlMatch->setPage($page, $matchedUrl);
    }

    private function getArticleFromUrl(string $url, UrlMatch $urlMatch): void {
        $urlParts = UrlHelper::splitIntoParts($url);
        $article = null;

        $matchedUrl = $url;
        for ($i = 0; $i < count($urlParts); $i++) {
            $subArray = array_slice($urlParts, $i + 1, count($urlParts));
            $articlePartOfUrl = '/' . implode('/', $subArray);
            $elementHolderId = $this->friendlyUrlDao->getElementHolderIdFromUrl($articlePartOfUrl);
            $article = $this->articleDao->getArticleByElementHolderId($elementHolderId);
            if ($article && ($article->isPublished() || FrontendHelper::isPreviewMode())) {
                $matchedUrl = $articlePartOfUrl;
                break;
            }
        }
        $urlMatch->setArticle($article, $matchedUrl);
    }
}
