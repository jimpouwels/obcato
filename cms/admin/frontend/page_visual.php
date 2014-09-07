<?php

    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/frontend/frontend_visual.php";

    class PageVisual extends FrontendVisual {

        private $_template_engine;
        private $_page;

        public function __construct($current_page) {
            parent::__construct($current_page);
            $this->_page = $current_page;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render()
        {
            $this->_template_engine->assign("elements", $this->renderPageContent());
            $this->_template_engine->assign("title", $this->_page->getTitle());
            $this->_template_engine->assign("navigation_title", $this->_page->getNavigationTitle());
            $this->_template_engine->assign("description", $this->toHtml($this->_page->getDescription(), $this->_page));
            $this->_template_engine->assign("show_in_navigation", $this->_page->getShowInNavigation());
            $this->_template_engine->display(TEMPLATE_DIR . $this->_page->getTemplate()->getFileName());
        }

        private function renderPageContent() {
            $elements_content = array();
            foreach ($this->_page->getElements() as $element)
               $elements_content[] = $element->getFrontendVisual($this->_current_page)->render();
            return $elements_content;
        }
    }