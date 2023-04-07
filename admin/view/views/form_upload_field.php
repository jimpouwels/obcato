<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class UploadField extends FormField {
    
        private static $TEMPLATE = "system/form_upload_field.tpl";

        public function __construct($name, $label, $mandatory, $class_name) {
            parent::__construct($name, null, $label, $mandatory, false, $class_name);
        }
    
        public function renderVisual(): string {
            parent::renderVisual();
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }