<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class Pulldown extends FormField {
    
        private array $_options;
    
        public function __construct(string $name, string $label, ?string $value, array $options, bool $mandatory, ?string $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
            $this->_options = $options;
        }
    
        public function getFormFieldTemplateFilename(): string {
            return "system/form_pulldown.tpl";
        }

        function loadFormField(Smarty_Internal_Data $data) {
            $data->assign("options", $this->_options);
        }
    
    }

?>