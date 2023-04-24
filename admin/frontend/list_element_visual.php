<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class ListElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, ListElement $list_element) {
            parent::__construct($page, $article, $list_element);
        }

        public function getElementTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName();
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $element_holder = $this->getElement()->getElementHolder();
            $data->getTemplateEngine()->assign("title", $this->toHtml($this->getElement()->getTitle(), $element_holder));
            $data->getTemplateEngine()->assign("items", $this->renderListItems($element_holder));
        }

        private function renderListItems(ElementHolder $element_holder): array {
            $list_items = array();
            foreach ($this->getElement()->getListItems() as $list_item) {
                $list_items[] = $this->toHtml($list_item->getText(), $element_holder);
            }
            return $list_items;
        }
    }