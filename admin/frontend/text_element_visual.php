<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/element_visual.php";

class TextElementFrontendVisual extends ElementFrontendVisual {

    public function __construct(Page $page, ?Article $article, TextElement $text_element) {
        parent::__construct($page, $article, $text_element);
    }

    public function loadElement(): void {
        $this->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
        $this->assign("text", $this->toHtml($this->getElement()->getText(), $this->getElementHolder()));
    }
}

?>