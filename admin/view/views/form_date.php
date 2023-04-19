<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_textfield.php";
    
    class DateField extends FormField {
    
        private static $TEMPLATE = "system/form_date.tpl";
    
        public function __construct(string $name, string $label, string $value, bool $mandatory, ?string $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
        }
    
        public function render(): string {
            return parent::render() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }