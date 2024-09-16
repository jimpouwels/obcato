<?php

namespace Obcato\Core\elements\list_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\list_element\visuals\ListElementEditor;
use Obcato\Core\elements\list_element\visuals\ListElementStatics;
use Obcato\Core\frontend\FrontendVisual;
use Obcato\Core\frontend\ListElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class ListElement extends Element {

    private array $listItems = array();

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ListElementMetaDataProvider($this));
    }

    public function getListItems(): array {
        return $this->listItems;
    }

    public function setListItems(array $listItems): void {
        $this->listItems = $listItems;
    }

    public function addListItem(): void {
        $listItem = new ListItem();
        $listItem->setIndent(0);
        $this->listItems[] = $listItem;
    }

    public function deleteListItem(ListItem $listItemToDelete): void {
        $this->listItems = array_filter($this->listItems, function ($listItem) use ($listItemToDelete) {
            return $listItem->getId() !== $listItemToDelete->getId();
        });
        $this->getMetaDataProvider()->deleteListItem($listItemToDelete);
    }

    public function getStatics(): Visual {
        return new ListElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ListElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article, ?Block $block = null): FrontendVisual {
        return new ListElementFrontendVisual($page, $article, $block, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ListElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return $this->getTitle() || '';
    }
}

