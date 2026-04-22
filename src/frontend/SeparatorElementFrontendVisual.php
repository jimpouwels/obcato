<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\elements\separator_element\SeparatorElement;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;

class SeparatorElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, SeparatorElement $separatorElement) {
        parent::__construct($page, $article, $block, $separatorElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle());
    }

}