<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\table_of_contents_element\TableOfContentsElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class TableOfContentsElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, TableOfContentsElement $element) {
        parent::__construct($page, $article, $element);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->getElement()->getTitle();
        $data["items"] = $this->renderItems();
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