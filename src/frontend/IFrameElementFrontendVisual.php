<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\elements\iframe_element\IFrameElement;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\blocks\model\Block;
use Pageflow\Core\modules\pages\model\Page;

class IFrameElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, ?Block $block, IFrameElement $iframeElement) {
        parent::__construct($page, $article, $block, $iframeElement);
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->getElement()->getTitle();
        $data["url"] = $this->getElement()->getUrl();
        $data["height"] = $this->getElement()->getHeight();
        $data["width"] = $this->getElement()->getWidth();
    }

}