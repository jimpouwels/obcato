<?php

namespace Obcato\Core\view\views;

class WarningMessage extends Visual {

    private string $_message_resource_identifier;

    public function __construct(string $message_resrouceIdentifier) {
        parent::__construct();
        $this->_message_resource_identifier = $message_resrouceIdentifier;
    }

    public function getTemplateFilename(): string {
        return "warning_message.tpl";
    }

    public function load(): void {
        $this->assign("message_resource_identifier", $this->_message_resource_identifier);
    }
}