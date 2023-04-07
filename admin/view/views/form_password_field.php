<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_textfield.php";
    
    class PasswordField extends FormField {
    
        private static $TEMPLATE = "system/form_password.tpl";

        public function __construct($name, $label, $value, $mandatory, $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
        }
    
        public function renderVisual(): string {
            return parent::renderVisual() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }

?>
