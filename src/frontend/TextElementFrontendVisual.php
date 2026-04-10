<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\text_element\TextElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class TextElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, TextElement $element) {
        parent::__construct($page, $article, $block, $element);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle());
        $data["text"] = $this->toHtml($this->getElement()->getText());
        $data["text_wysiwyg"] = $this->getElement()->getText();
    }
}
