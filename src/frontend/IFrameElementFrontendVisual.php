<?php

namespace Obcato\Core\frontend;

use Obcato\Core\elements\iframe_element\IFrameElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;

class IFrameElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, IFrameElement $iframeElement) {
        parent::__construct($page, $article, $iframeElement);
    }

    public function loadElement(): void {
        $this->assign("title", $this->getElement()->getTitle());
        $this->assign("url", $this->toHtml($this->getElement()->getUrl(), $this->getElementHolder()));
        $this->assign("height", $this->getElement()->getHeight());
        $this->assign("width", $this->getElement()->getWidth());
    }

}