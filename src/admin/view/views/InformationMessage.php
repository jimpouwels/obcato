<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class InformationMessage extends Visual {

    private string $_message;

    public function __construct(TemplateEngine $templateEngine, string $message) {
        parent::__construct($templateEngine);
        $this->_message = $message;
    }

    public function getTemplateFilename(): string {
        return "system/information_message.tpl";
    }

    public function load(): void {
        $this->assign("message", $this->_message);
    }
}