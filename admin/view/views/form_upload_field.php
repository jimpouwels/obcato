<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class UploadField extends FormField {
    
        public function __construct(string $name, string $label, bool $mandatory, ?string $class_name) {
            parent::__construct($name, null, $label, $mandatory, false, $class_name);
        }
    
        public function getFormFieldTemplateFilename(): string {
            return "system/form_upload_field.tpl";
        }

        function loadFormField(Smarty_Internal_Data $data) {
        }
    
    }