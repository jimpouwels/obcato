<?php

class FormLabel extends Visual {

    private string $_field_name;
    private string $_label_resource_identifier;
    private bool $_mandatory;

    public function __construct(string $field_name, string $labelResourceIdentifier, bool $mandatory) {
        parent::__construct();
        $this->_field_name = $field_name;
        $this->_label_resource_identifier = $labelResourceIdentifier;
        $this->_mandatory = $mandatory;
    }

    public function getTemplateFilename(): string {
        return "system/form_label.tpl";
    }

    public function load(): void {
        $this->assign("label_resource_identifier", $this->_label_resource_identifier);
        $this->assign("name", $this->_field_name);
        $this->assign("mandatory", $this->_mandatory);
    }
}