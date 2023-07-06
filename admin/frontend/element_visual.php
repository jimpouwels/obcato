<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";

    abstract class ElementFrontendVisual extends FrontendVisual {

        private Element $_element;

        public function __construct(Page $page, ?Article $article, Element $element) {
            parent::__construct($page, $article);
            $this->_element = $element;
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/element.tpl";
        }

        public function getElementTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName();
        }

        abstract function loadElement(Smarty_Internal_Data $data): void;

        public function load(): void {
            $this->assign("toc_reference", $this->toAnchorValue($this->_element->getTitle()));
            $this->assign("include_in_table_of_contents", $this->_element->includeInTableOfContents());
            $this->assign("type", $this->_element->getType()->getIdentifier());
            
            $element_data = $this->getTemplateEngine()->createChildData();
            $this->loadElement($element_data);
            
            $this->assign("element_html", $this->getTemplateEngine()->fetch($this->getElementTemplateFilename(), $element_data));
        }

        protected function getElement(): Element {
            return $this->_element;
        }

    }