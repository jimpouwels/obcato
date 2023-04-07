<?php

    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/form_textarea.php";

    class TextElementEditorVisual extends ElementVisual {
    
        private static $TEMPLATE = "elements/text_element/text_element_form.tpl";
    
        private $_text_element;
    
        public function __construct($text_element) {
            parent::__construct();
            $this->_text_element = $text_element;
        }
    
        public function getElement(): Element {
            return $this->_text_element;
        }
        
        public function renderElementForm() {
            $title_field = new TextField('element_' . $this->_text_element->getId() . '_title', 'Titel', $this->_text_element->getTitle(), false, true, null);
            $text_field = new TextArea('element_' . $this->_text_element->getId() . '_text', 'Tekst', $this->_text_element->getText(), false, true, null);
            
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("text_field", $text_field->render());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
    
?>