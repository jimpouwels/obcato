<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/form_readonly_textfield.php";

    class ReadonlyTextField extends FormField {

        private static $TEMPLATE = 'system/form_readonly_textfield.tpl';

        public function __construct($name, $label, $value, $class_name) {
            parent::__construct($name, $value, $label, false, false, $class_name);
        }

        public function renderVisual(): string {
            return parent::renderVisual() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

    }
