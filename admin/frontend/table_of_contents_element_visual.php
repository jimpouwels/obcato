<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "frontend/element_visual.php";
require_once CMS_ROOT . 'database/dao/element_dao.php';

class TableOfContentsElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, TableOfContentsElement $table_of_contents_element) {
        parent::__construct($page, $article, $table_of_contents_element);
    }

    public function loadElement(): void {
        $this->assign("title", $this->getElement()->getTitle());
        $this->assign("items", $this->renderItems());
    }

    public function renderItems(): array {
        $items = array();
        foreach ($this->getElementHolder()->getElements() as $element) {
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
