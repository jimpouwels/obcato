<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class TextArea extends FormField {
    
    
        public function __construct(string $name, string $label, ?string $value, bool $mandatory, bool $linkable, ?string $class_name) {
            parent::__construct($name, $value, $label, $mandatory, $linkable, $class_name);
        }
    
        public function getFormFieldTemplateFilename(): string {
            return "system/form_textarea.tpl";
        }

        function loadFormField(Smarty_Internal_Data $data) {
        }
    
    }