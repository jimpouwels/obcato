<?php
require_once CMS_ROOT . "/frontend/FrontendVisual.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

class SitemapVisual extends FrontendVisual {

    private PageDao $pageDao;
    private ArticleDao $articleDao;

    public function __construct() {
        parent::__construct(null, null);
        $this->pageDao = PageDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/sitemap.tpl";
    }

    public function loadVisual(?array &$data): void {
        $this->assign('pages', $this->renderPageUrls());
        $this->assign('articles', $this->renderArticleUrls());
    }

    public function getPresentable(): ?Presentable {
        return null;
    }

    private function renderPageUrls(): array {
        $page_urls = array();
        foreach ($this->pageDao->getAllPages() as $page) {
            if (!$page->getIncludeInSearchEngine()) {
                continue;
            }
            $page_url = array();
            $page_url['url'] = $this->getPageUrl($page, true);
            $page_url['last_modified'] = date_format($page->getLastModified(), 'Y-m-d');
            $page_urls[] = $page_url;
        }
        return $page_urls;
    }

    private function renderArticleUrls(): array {
        $article_urls = array();
        foreach ($this->articleDao->getAllArticles() as $article) {
            $article_url = array();
            $article_url['url'] = $this->getArticleUrl($article, true);
            $article_url['last_modified'] = date_format($article->getLastModified(), 'Y-m-d');
            $article_urls[] = $article_url;
        }
        return $article_urls;
    }

}