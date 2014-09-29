<?php

    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/frontend/frontend_visual.php";
    require_once CMS_ROOT . "/database/dao/settings_dao.php";

    class PageVisual extends FrontendVisual {

        private $_template_engine;
        private $_page;
        private $_article;
        private $_settings_dao;

        public function __construct($current_page, $current_article) {
            parent::__construct($current_page);
            $this->_page = $current_page;
            $this->_article = $current_article;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_settings_dao = SettingsDao::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("website_title", $this->_settings_dao->getSettings()->getWebsiteTitle());
            $this->_template_engine->assign("page", $this->getPageData());
            return $this->_template_engine->display($this->getTemplateDir() . "/" . $this->_page->getTemplate()->getFileName());
        }

        private function getPageData() {
            $page_data = array();
            $page_data["children"] = $this->renderChildren($this->_page);
            $page_data["elements"] = $this->renderElementHolderContent($this->_page);
            $page_data["blocks"] = $this->renderBlocks();
            $page_data["article"] = $this->renderArticle();
            $page_data["title"] = $this->_page->getTitle();
            $page_data["navigation_title"] = $this->_page->getNavigationTitle();
            $page_data["description"] = $this->toHtml($this->_page->getDescription(), $this->_page);
            $page_data["show_in_navigation"] = $this->_page->getShowInNavigation();
            return $page_data;
        }

        private function renderChildren($page) {
            $children = array();
            foreach ($page->getSubPages() as $subPage) {
                if (!$subPage->isPublished()) continue;
                $child = array();
                $child["title"] = $subPage->getTitle();
                $child["navigation_title"] = $subPage->getNavigationTitle();
                $child["description"] = $subPage->getDescription();
                $child["show_in_navigation"] = $subPage->getShowInNavigation();
                $child["url"] = $this->getPageUrl($subPage);
                $child["children"] = $this->renderChildren($subPage);
                $children[] = $child;
            }
            return $children;
        }

        private function renderArticle() {
            $article_content = null;
            if (!is_null($this->_article) && $this->_article->isPublished()) {
                $article_content = array();
                $article_content["id"] = $this->_article->getId();
                $article_content["title"] = $this->_article->getTitle();
                $article_content["description"] = $this->_article->getDescription();
                $article_content["publication_date"] = $this->_article->getPublicationDate();
                $article_content["image"] = $this->getImageData($this->_article->getImage());
                $article_content["elements"] = $this->renderElementHolderContent($this->_article);
            }
            return $article_content;
        }

        private function renderBlocks() {
            $blocks = array();
            $blocks["no_position"] = array();
            foreach ($this->_page->getBlocks() as $block) {
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
            $block_content = array();
            $block_content["id"] = $block->getId();
            $block_content["title"] = $block->getTitle();
            $block_content["elements"] = $this->renderElementHolderContent($block);
            return $block_content;
        }

        private function renderElementHolderContent($element_holder) {
            $elements_content = array();
            foreach ($element_holder->getElements() as $element)
               $elements_content[] = $element->getFrontendVisual($element_holder)->render();
            return $elements_content;
        }

        private function getImageData($image) {
            $image_data = null;
            if (!is_null($image)) {
                $image_data = array();
                $image_data["title"] = $image->getTitle();
                $image_data["url"] = $this->getImageUrl($image);
            }
            return $image_data;
        }
    }