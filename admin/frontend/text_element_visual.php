<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class TextElementFrontendVisual extends ElementFrontendVisual {

        public function __construct(Page $page, ?Article $article, TextElement $text_element) {
            parent::__construct($page, $article, $text_element);
        }

        public function renderElement(): string {
            $element_holder = $this->getElement()->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->toHtml($this->getElement()->getTitle(), $element_holder));
            $this->getTemplateEngine()->assign("text", $this->toHtml($this->getElement()->getText(), $element_holder));
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->getElement()->getTemplate()->getFileName());
        }
    }
    
?>