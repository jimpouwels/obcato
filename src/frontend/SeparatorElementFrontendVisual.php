<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\separator_element\SeparatorElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class SeparatorElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, SeparatorElement $separatorElement) {
        parent::__construct($page, $article, $separatorElement);
    }

    public function loadElement(): void {
    }

}