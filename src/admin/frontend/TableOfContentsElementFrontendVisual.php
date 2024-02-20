<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\elements\table_of_contents_element\TableOfContentsElement;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;

class TableOfContentsElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, TableOfContentsElement $element) {
        parent::__construct($page, $article, $element);
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