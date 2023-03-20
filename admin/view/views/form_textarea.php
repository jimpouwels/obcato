<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class TextArea extends FormField {
    
        private static $TEMPLATE = "system/form_textarea.tpl";

        private $_template_engine;
    
        public function __construct($name, $label, $value, $mandatory, $linkable, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, $linkable, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
        }
    
        public function render() {
            parent::render();
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }