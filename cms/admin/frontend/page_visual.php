<?php

    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/view/views/visual.php";

    class PageVisual extends Visual {

        private $_template_engine;
        private $_page;

        public function __construct($page) {
            $this->_page = $page;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render()
        {
            $this->_template_engine->assign("title", $this->_page->getTitle());
            $this->_template_engine->display(TEMPLATE_DIR . $this->_page->getTemplate()->getFileName());
        }
    }