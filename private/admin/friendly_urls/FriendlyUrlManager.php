<?php
require_once CMS_ROOT . '/database/dao/FriendlyUrlDaoMysql.php';
require_once CMS_ROOT . '/database/dao/PageDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ArticleDaoMysql.php';
require_once CMS_ROOT . '/friendly_urls/UrlMatch.php';
require_once CMS_ROOT . '/utilities/UrlHelper.php';

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
        $this->writeHtaccessFileIfNotExists();
    }

    public static function getInstance(): FriendlyUrlManager {
        if (!self::$instance) {
            self::$instance = new FriendlyUrlManager(FriendlyUrlDaoMysql::getInstance(),
                PageDaoMysql::getInstance(),
                ArticleDaoMysql::getInstance());
        }
        return self::$instance;
    }

    private function writeHtaccessFileIfNotExists(): void {
        $public_root_dir = PUBLIC_DIR;
        $htaccess_file_path = $public_root_dir . '/.htaccess';
        if (file_exists($htaccess_file_path)) return;
        $handle = fopen($htaccess_file_path, 'w');
        fclose($handle);
        file_put_contents($htaccess_file_path, "RewriteEngine on\n\n" .
            "RewriteCond %{HTTP_HOST} !=localhost\n" .
            "RewriteCond %{HTTPS} !=on\n" .
            "RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]\n\n" .
            "RewriteCond %{REQUEST_URI} !^/index.php\n" .
            "RewriteRule ^sitemap.xml$ /index.php?sitemap=true [NC,L]\n\n" .
            "RewriteCond %{REQUEST_URI} !^/index(.*)\n" .
            "RewriteCond %{REQUEST_URI} !^/static(.*)\n" .
            "RewriteCond %{REQUEST_URI} !^/upload(.*)\n" .
            "RewriteCond %{REQUEST_URI} !^/admin(.*)\n" .
            "RewriteRule ^.*$ index.php [NC,L]");
    }

    public function insertOrUpdateFriendlyUrlForPage(Page $page): void {
        $url = $this->createUrlForPage($page);
        $this->insertOrUpdateFriendlyUrl($url, $page);
    }

    private function createUrlForPage(Page $page): string {
        $url = '/' . $this->replaceSpecialCharacters($page->getNavigationTitle());
        $parent_page = $this->pageDao->getParent($page);
        if ($parent_page != null && $parent_page->getId() != $this->pageDao->getRootPage()->getId()) {
            $url = $this->createUrlForPage($this->pageDao->getParent($page)) . $url;
        }
        return $url;
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
        $value = urlencode($value);
        return $value;
    }

    private function insertOrUpdateFriendlyUrl(string $url, ElementHolder $element_holder): void {
        $url = $this->appendNumberIfFriendlyUrlExists($url, $element_holder);
        if (!$this->getFriendlyUrlForElementHolder($element_holder)) {
            $this->friendlyUrlDao->insertFriendlyUrl($url, $element_holder);
        } else {
            $this->friendlyUrlDao->updateFriendlyUrl($url, $element_holder);
        }
    }

    private function appendNumberIfFriendlyUrlExists(string $url, ElementHolder $element_holder): string {
        $new_url = $url;
        $existing_element_holder_id = $this->friendlyUrlDao->getElementHolderIdFromUrl($url);
        $number = 1;
        while ($existing_element_holder_id != null && $existing_element_holder_id != $element_holder->getId()) {
            $new_url = $url . $number;
            $number++;
            $existing_element_holder_id = $this->friendlyUrlDao->getElementHolderIdFromUrl($new_url);
        }
        return $new_url;
    }

    public function getFriendlyUrlForElementHolder(ElementHolder $element_holder): ?string {
        return $this->friendlyUrlDao->getUrlFromElementHolder($element_holder);
    }

    public function insertOrUpdateFriendlyUrlForArticle(Article $article): void {
        $url = $this->createUrlForArticle($article);
        $this->insertOrUpdateFriendlyUrl($url, $article);
    }

    private function createUrlForArticle(Article $article): string {
        $base = $article->getParentArticleId() ? ($this->getFriendlyUrlForElementHolder($this->articleDao->getArticle($article->getParentArticleId())) . '/') : '/';
        return $base . $this->replaceSpecialCharacters($article->getTitle());
    }

    public function matchUrl(string $url): ?UrlMatch {
        if (str_ends_with($url, '/')) {
            $url = rtrim($url, "/");
        }
        $url_match = new UrlMatch();
        $this->getPageFromUrl($url, $url_match);

        if (!$url_match->getPage()) {
            return null;
        }
        if (strlen($url_match->getPageUrl()) < strlen($url)) {
            $this->getArticleFromUrl($url, $url_match);
            if (!$url_match->getArticle()) {
                return null;
            }
        }
        return $url_match;
    }

    private function getPageFromUrl(string $url, UrlMatch $url_match): void {
        $url = UrlHelper::removeQueryStringFrom($url);
        $element_holder_id = $this->friendlyUrlDao->getElementHolderIdFromUrl($url);
        $page = $this->pageDao->getPageByElementHolderId($element_holder_id);

        $matched_url = $url;
        if (is_null($page)) {
            $url_parts = UrlHelper::splitIntoParts($url);
            for ($i = 0; $i < count($url_parts); $i++) {
                $sub_array = array_slice($url_parts, 1, (count($url_parts) - $i - 1));
                $page_part_of_url = '/' . implode('/', $sub_array);
                $element_holder_id = $this->friendlyUrlDao->getElementHolderIdFromUrl($page_part_of_url);
                $page = $this->pageDao->getPageByElementHolderId($element_holder_id);
                if ($page) {
                    $matched_url = $page_part_of_url;
                    break;
                }
            }
        }
        $url_match->setPage($page, $matched_url);
    }

    private function getArticleFromUrl(string $url, UrlMatch $url_match): void {
        $url_parts = UrlHelper::splitIntoParts($url);
        $article = null;

        $matched_url = $url;
        for ($i = 0; $i < count($url_parts); $i++) {
            $sub_array = array_slice($url_parts, $i + 1, count($url_parts));
            $article_part_of_url = '/' . implode('/', $sub_array);
            $element_holder_id = $this->friendlyUrlDao->getElementHolderIdFromUrl($article_part_of_url);
            $article = $this->articleDao->getArticleByElementHolderId($element_holder_id);
            if ($article) {
                $matched_url = $article_part_of_url;
                break;
            }
        }
        $url_match->setArticle($article, $matched_url);
    }
}
