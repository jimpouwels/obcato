<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\elements\table_of_contents_element\TableOfContentsElement;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;

class TableOfContentsElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, TableOfContentsElement $element) {
        parent::__construct($page, $article, $block, $element);
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