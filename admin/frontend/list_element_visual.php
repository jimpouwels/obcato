<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class ListElementFrontendVisual extends ElementFrontendVisual {

        private ListElement $_list_element;

        public function __construct(Page $current_page, ListElement $list_element) {
            parent::__construct($current_page, $list_element);
            $this->_list_element = $list_element;
        }

        public function renderElement(): string {
            $element_holder = $this->_list_element->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->toHtml($this->_list_element->getTitle(), $element_holder));
            $this->getTemplateEngine()->assign("items", $this->renderListItems($element_holder));
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_list_element->getTemplate()->getFileName());
        }

        private function renderListItems(ElementHolder $element_holder): array {
            $list_items = array();
            foreach ($this->_list_element->getListItems() as $list_item) {
                $list_items[] = $this->toHtml($list_item->getText(), $element_holder);
            }
            return $list_items;
        }
    }