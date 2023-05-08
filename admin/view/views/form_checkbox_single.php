<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class SingleCheckbox extends FormField {
    
        private string $_onchange_js = '';
    
        public function __construct(string $name, string $label_identifier, string $value, bool $mandatory, ?string $class_name) {
            parent::__construct($name, $value, $label_identifier, $mandatory, false, $class_name);
        }
    
        public function getFormFieldTemplateFilename(): string {
            return "system/form_checkbox_single.tpl";
        }

        function loadFormField(Smarty_Internal_Data $data) {
            $data->assign('onchange_js', "onChange=({$this->_onchange_js})");
        }

        public function setOnChangeJS(string $onchange_js): void {
            $this->_onchange_js = $onchange_js;
        }

    }

?>