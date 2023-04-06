<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";

    abstract class ElementFrontendVisual extends FrontendVisual {

        private $_template_engine;
        private $_element;

        public function __construct($current_page, $element) {
            parent::__construct($current_page);
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_element = $element;
        }

        public abstract function renderElement(): string;

        public function render(): string {
            $this->_template_engine->assign("toc_reference", $this->toAnchorValue($this->_element->getTitle()));
            $this->_template_engine->assign("include_in_table_of_contents", $this->_element->includeInTableOfContents());
            $this->_template_engine->assign("element_html", $this->renderElement());
            return $this->_template_engine->fetch(FRONTEND_TEMPLATE_DIR . "/element.tpl");
        }

    }