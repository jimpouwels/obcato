<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\iframe_element\IFrameElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class IFrameElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, IFrameElement $iframeElement) {
        parent::__construct($page, $article, $block, $iframeElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->getElement()->getTitle();
        $data["url"] = $this->toHtml($this->getElement()->getUrl(), $this->getElementHolder());
        $data["height"] = $this->getElement()->getHeight();
        $data["width"] = $this->getElement()->getWidth();
    }

}