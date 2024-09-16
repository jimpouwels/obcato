<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\separator_element\SeparatorElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class SeparatorElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, SeparatorElement $separatorElement) {
        parent::__construct($page, $article, $block, $separatorElement);
    }

    public function loadElement(array &$data): void {
    }

}