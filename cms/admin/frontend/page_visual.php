<?php

    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/frontend/frontend_visual.php";

    class PageVisual extends FrontendVisual {

        private $_template_engine;
        private $_page;
        private $_article;

        public function __construct($current_page, $current_article) {
            parent::__construct($current_page);
            $this->_page = $current_page;
            $this->_article = $current_article;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("children", $this->renderChildren($this->_page));
            $this->_template_engine->assign("elements", $this->renderElementHolderContent($this->_page));
            $this->_template_engine->assign("article", $this->renderArticle());
            $this->_template_engine->assign("title", $this->_page->getTitle());
            $this->_template_engine->assign("navigation_title", $this->_page->getNavigationTitle());
            $this->_template_engine->assign("description", $this->toHtml($this->_page->getDescription(), $this->_page));
            $this->_template_engine->assign("show_in_navigation", $this->_page->getShowInNavigation());
            $this->_template_engine->display($this->getTemplateDir() . "/" . $this->_page->getTemplate()->getFileName());
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