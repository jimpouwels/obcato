<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class FormError extends Visual {

    private string $_message;

    public function __construct(TemplateEngine $templateEngine, string $message) {
        parent::__construct($templateEngine);
        $this->_message = $message;
    }

    public function getTemplateFilename(): string {
        return "system/form_error.tpl";
    }

    public function load(): void {
        $this->assign("error", $this->_message);
    }

}