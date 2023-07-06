<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class TextElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, TextElement $text_element) {
            parent::__construct($page, $article, $text_element);
        }

        public function loadElement(Smarty_Internal_Data $data): void {
            $data->assign("title", $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder()));
            $data->assign("text", $this->toHtml($this->getElement()->getText(), $this->getElementHolder()));
        }
    }
    
?>