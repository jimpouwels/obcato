<?php
    defined('_ACCESS') or die;

    class FormLabel extends Visual {
        
        private string $_field_name;
        private string $_field_label;
        private bool $_mandatory;

        public function __construct(string $field_name, string $field_label, bool $mandatory) {
            parent::__construct();
            $this->_field_name = $field_name;
            $this->_field_label = $field_label;
            $this->_mandatory = $mandatory;
        }

        public function getTemplateFilename(): string {
            return "system/form_label.tpl";
        }

        public function load(): void {
            $this->assign("label", $this->_field_label);
            $this->assign("name", $this->_field_name);
            $this->assign("mandatory", $this->_mandatory);
        }
    }

?>