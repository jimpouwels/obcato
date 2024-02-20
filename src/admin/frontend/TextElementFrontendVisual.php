<?php

namespace Obcato\Core\admin\frontend;

use Obcato\Core\admin\elements\text_element\TextElement;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;

class TextElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, TextElement $element) {
        parent::__construct($page, $article, $element);
    }

    public function loadElement(): void {
        $this->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
        $this->assign("text", $this->toHtml($this->getElement()->getText(), $this->getElementHolder()));
    }
}