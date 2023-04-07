<?php
    defined('_ACCESS') or die;

    class PageTree extends Panel {

        private $PAGES_TREE_TEMPLATE = "pages/tree.tpl";
        private $PAGES_TREE_ITEM_TEMPLATE = "pages/tree_item.tpl";

        private $_root_page;
        private $_selected_page;

        public function __construct($root_page, $selected_page) {
            parent::__construct($this->getTextResource('page_tree_title'), 'page_tree_fieldset');
            $this->_root_page = $root_page;
            $this->_selected_page = $selected_page;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("items_html", $this->renderPageTree($this->_root_page));
            return $this->getTemplateEngine()->fetch("modules/" . $this->PAGES_TREE_TEMPLATE);
        }

        private function renderPageTree($page): string {
            $sub_pages = array();
            foreach ($page->getSubPages() as $sub_page) {
                $sub_pages[] = $this->renderPageTree($sub_page);
            }

            $this->getTemplateEngine()->assign("sub_pages", $sub_pages);
            $this->getTemplateEngine()->assign("title", $page->getTitle());
            $this->getTemplateEngine()->assign("show_in_navigation", $page->getShowInNavigation());
            $this->getTemplateEngine()->assign("published", $page->isPublished());
            $this->getTemplateEngine()->assign("page_id", $page->getId());

            $active = $this->_selected_page->getId() == $page->getId();
            $this->getTemplateEngine()->assign("active", $active);

            return $this->getTemplateEngine()->fetch("modules/" . $this->PAGES_TREE_ITEM_TEMPLATE);
        }

    }

?>
