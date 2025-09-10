<?php

namespace Obcato\Core\view\views;

class FormError extends Visual {

    private string $_message;

    public function __construct(string $message) {
        parent::__construct();
        $this->_message = $message;
    }

    public function getTemplateFilename(): string {
        return "form_error.tpl";
    }

    public function load(): void {
        $this->assign("error", $this->_message);
    }

}