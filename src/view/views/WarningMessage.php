<?php

namespace Obcato\Core\view\views;

class WarningMessage extends Visual {

    private string $_message_resource_identifier;

    public function __construct(string $message_resource_identifier) {
        parent::__construct();
        $this->_message_resource_identifier = $message_resource_identifier;
    }

    public function getTemplateFilename(): string {
        return "system/warning_message.tpl";
    }

    public function load(): void {
        $this->assign("message_resource_identifier", $this->_message_resource_identifier);
    }
}