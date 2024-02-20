<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;

class ActionButtonSave extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_save'), $id, 'icon_apply');
    }

}