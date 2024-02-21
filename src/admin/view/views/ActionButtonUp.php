<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class ActionButtonUp extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_up'), $id, 'icon_moveup');
    }

}