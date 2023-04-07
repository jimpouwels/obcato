<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_textfield.php";
    
    class DateField extends FormField {
    
        private static $TEMPLATE = "system/form_date.tpl";
    
        private $_template_engine;
    
        public function __construct($name, $label, $value, $mandatory, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
        }
    
        public function renderVisual(): string {
            return parent::renderVisual() . $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }