<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . "/frontend/frontend_visual.php";
require_once CMS_ROOT . "/frontend/block_visual.php";
require_once CMS_ROOT . "/frontend/article_visual.php";
require_once CMS_ROOT . "/frontend/form_visual.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";

class PageVisual extends FrontendVisual {
    private PageDao $_page_dao;
    private BlockDao $_block_dao;

    private ArticleDao $_article_dao;
    private TemplateDao $_template_dao;

    public function __construct(Page $page, ?Article $article) {
        parent::__construct($page, $article);
        $this->_page_dao = PageDaoMysql::getInstance();
        $this->_block_dao = BlockDaoMysql::getInstance();
        $this->_article_dao = ArticleDaoMysql::getInstance();
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getPage()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $this->assign("website_title", WEBSITE_TITLE);
        $this->assign("page", $this->getPageContentAndMetaData($this->getPage()));
        $this->assign("title", $this->getPage()->getTitle());
        $this->assign("crumb_path", $this->renderCrumbPath());
        $this->assign("keywords", $this->getPage()->getKeywords());
        if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
            $article_data = $this->renderArticle();
            $this->assign("article", $article_data);
            $this->assign("title", $this->getArticle()->getTitle());
            $this->assign("keywords", $this->getArticle()->getKeywords());
        } else {
            $this->assign("article", null);
        }
        $this->assign("canonical_url", $this->getCanonicalUrl());
        $this->assign("root_page", $this->getPageMetaData($this->_page_dao->getRootPage()));
    }

    public function getPresentable(): ?Presentable {
        return $this->getPage();
    }

    private function getPageContentAndMetaData(Page $page): array {
        $page_data = array();
        $page_data["elements"] = $this->renderElementHolderContent($page);
        $page_data["blocks"] = $this->renderBlocks();
        $this->addPageMetaData($page, $page_data);
        return $page_data;
    }

    private function getPageMetaData(Page $page): array {
        $page_data = array();
        $this->addPageMetaData($page, $page_data);
        return $page_data;
    }

    private function renderChildren(Page $page): array {
        $children = array();
        foreach ($this->_page_dao->getSubPages($page) as $subPage) {
            if (!$subPage->isPublished()) continue;
            $child = array();
            $this->addPageMetaData($subPage, $child, false);
            $children[] = $child;
        }
        return $children;
    }

    private function addPageMetaData(Page $page, array &$page_data, bool $render_childen = true): void {
        $page_data["is_current_page"] = $this->getPage()->getId() == $page->getId();
        $page_data["title"] = $page->getTitle();
        $page_data["keywords"] = $page->getKeywords();
        $page_data["url"] = $this->getPageUrl($page);
        $page_data["is_homepage"] = $page->isHomepage();
        $page_data["navigation_title"] = $page->getNavigationTitle();
        $page_description = $page->getDescription();
        if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
            $page_description = $this->getArticle()->getDescription();
        }
        $page_data["description"] = $this->toHtml($page_description, $page);
        $page_data["show_in_navigation"] = $page->getShowInNavigation();
        if ($render_childen) {
            $page_data["children"] = $this->renderChildren($page);
        }
    }

    private function renderBlocks(): array {
        $blocks = array();
        $blocks['no_position'] = array();
        foreach ($this->_block_dao->getBlocksByPage($this->getPage()) as $block) {
            if (!$block->isPublished()) continue;
            $position = $block->getPosition();
            if (!is_null($position)) {
                $positionName = $position->getName();
                if (!isset($blocks[$positionName])) {
                    $blocks[$positionName] = array();
                }
                $blocks[$positionName][] = $this->renderBlock($block);
            } else {
                $blocks["no_position"][] = $this->renderBlock($block);
            }
        }
        return $blocks;
    }

    private function renderBlock($block): string {
        $block_visual = new BlockVisual($block, $this->getPage());
        return $block_visual->render();
    }

    private function renderArticle(): array {
        $article_data = array();

        $article_visual = new ArticleVisual($this->getPage(), $this->getArticle());
        $article_html = $article_visual->render($article_data);
        $article_data["to_string"] = $article_html;
        return $article_data;
    }

    private function renderElementHolderContent(ElementHolder $element_holder) {
        $elements_content = array();
        foreach ($element_holder->getElements() as $element) {
            $element_data = array();
            $element_data["type"] = $element->getType()->getIdentifier();
            if ($element->getTemplate()) {
                $element_data["to_string"] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
            }
            $elements_content[] = $element_data;
        }
        return $elements_content;
    }

    private function renderCrumbPath(): array {
        $crumb_path_items = array();
        $parent_article = null;
        if ($this->getArticle() && $this->getArticle()->getParentArticleId()) {
            $parent_article = $this->_article_dao->getArticle($this->getArticle()->getParentArticleId());
            $parents = $this->_page_dao->getParents($this->_page_dao->getPage($parent_article->getTargetPageId()));
        } else {
            $parents = $this->_page_dao->getParents($this->getPage());
        }
        for ($i = 0; $i < count($parents); $i++) {
            if ($this->getPage()->getId() == $parents[$i]->getId() && !$this->getArticle()) {
                continue;
            }
            $item_data = array();
            $item_data['url'] = $this->getPageUrl($parents[$i]);
            $item_data['title'] = $parents[$i]->getNavigationTitle();
            $crumb_path_items[] = $item_data;
        }
        if ($parent_article) {
            $item_data = array();
            $item_data['url'] = $this->getArticleUrl($parent_article);
            $item_data['title'] = $parent_article->getTitle();
            $crumb_path_items[] = $item_data;
        }
        return $crumb_path_items;
    }

}
