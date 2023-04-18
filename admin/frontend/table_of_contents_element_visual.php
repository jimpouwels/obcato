<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class TableOfContentsElementFrontendVisual extends ElementFrontendVisual {

        private TableOfContentsElement $_table_of_contents_element;

        public function __construct(Page $current_page, TableOfContentsElement $_table_of_contents_element) {
            parent::__construct($current_page, $_table_of_contents_element);
            $this->_table_of_contents_element = $_table_of_contents_element;
        }

        public function renderElement(): string {
            $this->getTemplateEngine()->assign("title", $this->_table_of_contents_element->getTitle());
            $this->getTemplateEngine()->assign("items", $this->renderItems());
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_table_of_contents_element->getTemplate()->getFileName());
        }

        public function renderItems(): array {
            $items = array();
            foreach ($this->getPage()->getElements() as $element) {
                if ($element->includeInTableOfContents()) {
                    $item = array();
                    $item["title"] = $element->getTitle();
                    $item["reference"] = $this->toAnchorValue($element->getTitle());
                    $items[] = $item;
                }
            }
            return $items;
        }

    }

?>
