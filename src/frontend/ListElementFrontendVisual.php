<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\core\model\ElementHolder;
use Pageflow\Core\elements\list_element\ListElement;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;

class ListElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, ListElement $listElement) {
        parent::__construct($page, $article, $block, $listElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle());
        $data["items"] = $this->renderListItems($this->getElementHolder());
    }

    private function renderListItems(ElementHolder $element_holder): array {
        $listItems = array();
        
        foreach ($this->getElement()->getListItems() as $listItem) {
            $listItems[] = $this->toHtml($listItem->getText());
        }
        
        return $listItems;
    }
}