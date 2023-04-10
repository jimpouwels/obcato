<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class TextArea extends FormField {
    
        private static $TEMPLATE = "system/form_textarea.tpl";
    
        public function __construct($name, $label, $value, $mandatory, $linkable, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, $linkable, $class_name);
        }
    
        public function render(): string {
            return parent::render() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }