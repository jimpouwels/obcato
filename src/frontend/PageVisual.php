<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\BlackBoard;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\dao\BlockDao;
use Obcato\Core\database\dao\BlockDaoMysql;
use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\templates\model\Presentable;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

class PageVisual extends FrontendVisual {
    private PageService $pageService;
    private BlockDao $blockDao;
    private ArticleDao $articleDao;
    private TemplateDao $templateDao;

    public function __construct(Page $page, ?Article $article) {
        parent::__construct($page, $article);
        $this->pageService = PageInteractor::getInstance();
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getPage()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $this->assignGlobal("page", $this->getPageContentAndMetaData($this->getPage()));
        $this->assign("crumb_path", $this->renderCrumbPath());
        $this->assign("keywords", $this->getPage()->getKeywords());
        if ($this->getArticle()) {
            $articleData = $this->renderArticle();
            $this->assignGlobal("article", $articleData);
            $this->assign("title", $this->getArticle()->getTitle());
            $this->assign("keywords", $this->getArticle()->getKeywords());
        } else {
            $this->assign("article", null);
        }
        $this->assign("blocks", $this->renderBlocks());
        $this->assign("canonical_url", $this->getCanonicalUrl());
        $this->assign("root_page", $this->getPageMetaData($this->pageService->getRootPage()));
        $this->assign("sitewide_pages", $this->getSitewidePagesData());
    }

    public function getPresentable(): ?Presentable {
        return $this->getPage();
    }

    private function getPageContentAndMetaData(Page $page): array {
        $pageData = array();
        $this->renderElementHolderContent($page, $pageData);
        $this->addPageMetaData($page, $pageData);
        return $pageData;
    }

    private function getPageMetaData(Page $page): array {
        $pageData = array();
        $this->addPageMetaData($page, $pageData);
        return $pageData;
    }

    private function renderChildren(Page $page): array {
        $children = array();
        foreach ($this->pageService->getSubPages($page) as $subPage) {
            if (!$subPage->isPublished()) continue;
            $child = array();
            $this->addPageMetaData($subPage, $child, false);
            $children[] = $child;
        }
        return $children;
    }

    private function getSitewidePagesData(): array {
        $data = array();
        $pages = $this->pageService->getSitewidePages();
        foreach ($pages as $page) {
            if (!$page->isPublished()) continue;
            $pageData = array();
            $pageData['id'] = $page->getId();
            $pageData['title'] = $page->getTitle();
            $pageData['url'] = $this->getPageUrl($page);
            $data[] = $pageData;
        }
        return $data;
    }

    private function addPageMetaData(Page $page, array &$pageData, bool $renderChildren = true): void {
        $pageData["is_current_page"] = $this->getPage()->getId() == $page->getId();
        $pageData["title"] = $page->getTitle();
        $pageData["id"] = $page->getId();
        $pageData["keywords"] = $page->getKeywords();
        $pageData["url"] = $this->getPageUrl($page);
        $pageData["is_homepage"] = $page->isHomepage();
        $pageData["navigation_title"] = $page->getNavigationTitle();
        $page_description = $page->getDescription();
        if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
            $page_description = $this->getArticle()->getDescription();
        }
        $pageData["description"] = $this->toHtml($page_description, $page);
        $pageData["show_in_navigation"] = $page->getShowInNavigation();
        if ($renderChildren) {
            $pageData["children"] = $this->renderChildren($page);
        }
    }

    private function renderBlocks(): array {
        $blocks = array();
        $blocks['no_position'] = array();
        foreach ($this->blockDao->getBlocksByPage($this->getPage()) as $block) {
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

    private function renderBlock($block): array {
        $blockData = array();
        $blockVisual = new BlockVisual($block, $this->getPage(), $this->getArticle());
        $blockHtml = $blockVisual->render($blockData);
        $blockData["to_string"] = $blockHtml;
        return $blockData;
    }

    private function renderArticle(): array {
        $articleData = array();
        $articleVisual = new ArticleVisual($this->getPage(), $this->getArticle());
        $articleHtml = $articleVisual->render($articleData);
        $articleData["to_string"] = $articleHtml;
        return $articleData;
    }

    private function renderCrumbPath(): array {
        $crumbPathItems = array();
        $parentArticle = null;
        if ($this->getArticle() && $this->getArticle()->getParentArticleId()) {
            $parentArticle = $this->articleDao->getArticle($this->getArticle()->getParentArticleId());
            $parents = $this->pageService->getParents($this->pageService->getPageById($parentArticle->getTargetPageId()));
        } else {
            $parents = $this->pageService->getParents($this->getPage());
        }
        for ($i = 0; $i < count($parents); $i++) {
            if (($this->getPage()->getId() == $parents[$i]->getId() && !$this->getArticle()) || !$parents[$i]->getShowInNavigation()) {
                continue;
            }
            $itemData = array();
            $itemData['url'] = $this->getPageUrl($parents[$i]);
            $itemData['title'] = $parents[$i]->getNavigationTitle();
            $crumbPathItems[] = $itemData;
        }
        if ($parentArticle) {
            $itemData = array();
            $itemData['url'] = $this->getArticleUrl($parentArticle);
            $itemData['title'] = $parentArticle->getTitle();
            $crumbPathItems[] = $itemData;
        }
        return $crumbPathItems;
    }

}
