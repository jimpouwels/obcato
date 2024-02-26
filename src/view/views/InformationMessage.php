<?php

namespace Obcato\Core\view\views;

class InformationMessage extends Visual {

    private string $_message;

    public function __construct(string $message) {
        parent::__construct();
        $this->_message = $message;
    }

    public function getTemplateFilename(): string {
        return "system/information_message.tpl";
    }

    public function load(): void {
        $this->assign("message", $this->_message);
    }
}