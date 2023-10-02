<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/element_visual.php";

class IFrameElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, IFrameElement $iframe_element) {
        parent::__construct($page, $article, $iframe_element);
    }

    public function loadElement(): void {
        $this->assign("title", $this->getElement()->getTitle());
        $this->assign("url", $this->toHtml($this->getElement()->getUrl(), $this->getElementHolder()));
        $this->assign("height", $this->getElement()->getHeight());
        $this->assign("width", $this->getElement()->getWidth());
    }

}