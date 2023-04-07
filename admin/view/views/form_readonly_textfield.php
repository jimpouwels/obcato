<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/form_readonly_textfield.php";

    class ReadonlyTextField extends FormField {

        private static $TEMPLATE = 'system/form_readonly_textfield.tpl';
        private $_template_engine;

        public function __construct($name, $label, $value, $class_name) {
            parent::__construct($name, $value, $label, false, false, $class_name);
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual() . $this->_template_engine->fetch(self::$TEMPLATE);
        }

    }
