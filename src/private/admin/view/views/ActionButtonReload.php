<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;

class ActionButtonReload extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_reload'), $id, 'icon_reload');
    }

}