<?php

namespace Obcato\Core;

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
