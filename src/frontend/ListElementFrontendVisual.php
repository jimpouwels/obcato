<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\elements\list_element\ListElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class ListElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ListElement $listElement) {
        parent::__construct($page, $article, $listElement);
    }

    public function loadElement(): void {
        $this->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
        $this->assign("items", $this->renderListItems($this->getElementHolder()));
    }

    private function renderListItems(ElementHolder $element_holder): array {
        $listItems = array();
        foreach ($this->getElement()->getListItems() as $listItem) {
            $listItems[] = $this->toHtml($listItem->getText(), $element_holder);
        }
        return $listItems;
    }
}