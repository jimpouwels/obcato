<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

    class SitemapVisual extends FrontendVisual {

        private PageDao $_page_dao;
        private ArticleDao $_article_dao;

        public function __construct() {
            parent::__construct(null, null);
            $this->_page_dao = PageDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/sitemap.tpl";
        }

        public function loadVisual( ?array &$data): void {
            $this->assign('pages', $this->renderPageUrls());
            $this->assign('articles', $this->renderArticleUrls());
        }

        public function getPresentable(): ?Presentable {
            return null;
        }

        private function renderPageUrls(): array {
            $page_urls = array();
            foreach ($this->_page_dao->getAllPages() as $page) {
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
            foreach ($this->_article_dao->getAllArticles() as $article) {
                $article_url = array();
                $article_url['url'] = $this->getArticleUrl($article, true);
                $article_url['last_modified'] = date_format($article->getLastModified(), 'Y-m-d');
                $article_urls[] = $article_url;
            }
            return $article_urls;
        }

    }