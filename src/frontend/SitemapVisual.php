<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\dao\PageDao;
use Obcato\Core\database\dao\PageDaoMysql;
use Obcato\Core\modules\templates\model\Presentable;
use const use Obcato\Core\FRONTEND_TEMPLATE_DIR;

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
        $pageUrls = array();
        foreach ($this->pageDao->getAllPages() as $page) {
            if (!$page->getIncludeInSearchEngine()) {
                continue;
            }
            $pageUrl = array();
            $pageUrl['url'] = $this->getPageUrl($page, true);
            $pageUrl['last_modified'] = date_format($page->getLastModified(), 'Y-m-d');
            $pageUrls[] = $pageUrl;
        }
        return $pageUrls;
    }

    private function renderArticleUrls(): array {
        $articleUrls = array();
        foreach ($this->articleDao->getAllArticles() as $article) {
            $articleUrl = array();
            $articleUrl['url'] = $this->getArticleUrl($article, true);
            $articleUrl['last_modified'] = date_format($article->getLastModified(), 'Y-m-d');
            $articleUrls[] = $articleUrl;
        }
        return $articleUrls;
    }

}