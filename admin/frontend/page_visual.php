<?php
    defined("_ACCESS") or die;
    
    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . "frontend/block_visual.php";
    require_once CMS_ROOT . "frontend/article_visual.php";
    require_once CMS_ROOT . "frontend/form_visual.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";

    class PageVisual extends FrontendVisual {
        private PageDao $_page_dao;

        public function __construct(Page $page, ?Article $article) {
            parent::__construct($page, $article);
            $this->_page_dao = PageDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getPage()->getTemplate()->getFileName();
        }

        public function load(): void {
            $this->assign("website_title", WEBSITE_TITLE);
            $this->assign("page", $this->getPageContentAndMetaData($this->getPage()));
            $rendered_article = null;
            $this->assign("page_title", $this->getPage()->getTitle());
            if (!is_null($this->getArticle()) && $this->getArticle()->isPublished()) {
                $article_visual = new ArticleFrontendVisual($this->getPage(), $this->getArticle());
                $rendered_article = $article_visual->render();
                $this->assign("page_title", $this->getArticle()->getTitle());
            }
            $this->assign("canonical_url", $this->getCanonicalUrl());
            $this->assign('article', $rendered_article);
            $this->assign("root_page", $this->getPageMetaData($this->_page_dao->getRootPage()));
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
            foreach ($page->getSubPages() as $subPage) {
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
            $page_data["url"] = $this->getPageUrl($page);
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
            foreach ($this->getPage()->getBlocks() as $block) {
                if (!$block->isPublished()) continue;
                $position = $block->getPosition();
                if (!is_null($position)) {
                    $positionName = $position->getName();
                    if (!isset($blocks[$positionName]))
                        $blocks[$positionName] = array();
                    $blocks[$positionName][] = $this->renderBlock($block);
                } else {
                    $blocks["no_position"][] = $this->renderBlock($block);
                }
            }
            return $blocks;
        }

        private function renderBlock($block) {
            $block_visual = new BlockVisual($block, $this->getPage());
            return $block_visual->render();
        }

        private function renderElementHolderContent(ElementHolder $element_holder) {
            $elements_content = array();
            foreach ($element_holder->getElements() as $element) {
                if ($element->getTemplate()) {
                    $elements_content[] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
                }
            }
            return $elements_content;
        }
    }
