<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/element_visual.php";

    class TextElementFrontendVisual extends ElementFrontendVisual {

        private $_text_element;
    
        public function __construct($current_page, $text_element) {
            parent::__construct($current_page, $text_element);
            $this->_text_element = $text_element;
        }

        public function renderElement(): string {
            $element_holder = $this->_text_element->getElementHolder();
            $this->getTemplateEngine()->assign("title", $this->toHtml($this->_text_element->getTitle(), $element_holder));
            $this->getTemplateEngine()->assign("text", $this->toHtml($this->_text_element->getText(), $element_holder));
            return $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->_text_element->getTemplate()->getFileName());
        }
    }
    
?>