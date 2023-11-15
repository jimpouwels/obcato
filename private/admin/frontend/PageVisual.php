<?php
require_once CMS_ROOT . "/frontend/FrontendVisual.php";
require_once CMS_ROOT . "/frontend/BlockVisual.php";
require_once CMS_ROOT . "/frontend/ArticleVisual.php";
require_once CMS_ROOT . "/frontend/FormFrontendVisual.php";
require_once CMS_ROOT . "/modules/pages/service/PageInteractor.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";

class PageVisual extends FrontendVisual {
    private PageInteractor $pageService;
    private BlockDao $blockDao;

    private ArticleDao $articleDao;
    private TemplateDao $templateDao;
    private ElementDao $elementDao;
    private SettingsDao $settingsDao;

    public function __construct(Page $page, ?Article $article) {
        parent::__construct($page, $article);
        $this->pageService = PageInteractor::getInstance();
        $this->blockDao = BlockDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->settingsDao = SettingsDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getPage()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $this->assign("website_title", $this->settingsDao->getSettings()->getWebsiteTitle());
        $this->assign("page", $this->getPageContentAndMetaData($this->getPage()));
        $this->assign("title", $this->getPage()->getTitle());
        $this->assign("crumb_path", $this->renderCrumbPath());
        $this->assign("keywords", $this->getPage()->getKeywords());
        if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
            $articleData = $this->renderArticle();
            $this->assign("article", $articleData);
            $this->assign("title", $this->getArticle()->getTitle());
            $this->assign("keywords", $this->getArticle()->getKeywords());
        } else {
            $this->assign("article", null);
        }
        $this->assign("canonical_url", $this->getCanonicalUrl());
        $this->assign("root_page", $this->getPageMetaData($this->pageService->getRootPage()));
    }

    public function getPresentable(): ?Presentable {
        return $this->getPage();
    }

    private function getPageContentAndMetaData(Page $page): array {
        $pageData = array();
        $pageData["elements"] = $this->renderElementHolderContent($page);
        $pageData["blocks"] = $this->renderBlocks();
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

    private function addPageMetaData(Page $page, array &$pageData, bool $renderChildren = true): void {
        $pageData["is_current_page"] = $this->getPage()->getId() == $page->getId();
        $pageData["title"] = $page->getTitle();
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

    private function renderBlock($block): string {
        return (new BlockVisual($block, $this->getPage()))->render();
    }

    private function renderArticle(): array {
        $articleData = array();
        $articleVisual = new ArticleVisual($this->getPage(), $this->getArticle());
        $articleHtml = $articleVisual->render($articleData);
        $articleData["to_string"] = $articleHtml;
        return $articleData;
    }

    private function renderElementHolderContent(ElementHolder $elementHolder): array {
        $elementsContent = array();
        foreach ($elementHolder->getElements() as $element) {
            $elementData = array();
            $elementData["type"] = $this->elementDao->getElementTypeForElement($element->getId())->getIdentifier();
            if ($element->getTemplate()) {
                $elementData["to_string"] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
            }
            $elementsContent[] = $elementData;
        }
        return $elementsContent;
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
            if ($this->getPage()->getId() == $parents[$i]->getId() && !$this->getArticle()) {
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
