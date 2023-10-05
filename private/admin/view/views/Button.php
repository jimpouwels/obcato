<?php

class Button extends Visual {

    private ?string $_id;
    private string $_label_resource_identifier;
    private ?string $_onclick;

    public function __construct(?string $id, string $label_resource_identifier, ?string $onclick) {
        parent::__construct();
        $this->_id = $id;
        $this->_label_resource_identifier = $label_resource_identifier;
        $this->_onclick = $onclick;
    }

    public function getTemplateFilename(): string {
        return "system/button.tpl";
    }

    public function load(): void {
        $this->assign("id", $this->_id);
        $this->assign("label_resource_identifier", $this->_label_resource_identifier);
        $this->assign("onclick", $this->_onclick);
    }

}