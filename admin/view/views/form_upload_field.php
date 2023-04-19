<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class UploadField extends FormField {
    
        private static string $TEMPLATE = "system/form_upload_field.tpl";

        public function __construct(string $name, string $label, bool $mandatory, ?string $class_name) {
            parent::__construct($name, null, $label, $mandatory, false, $class_name);
        }
    
        public function render(): string {
            parent::render();
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }