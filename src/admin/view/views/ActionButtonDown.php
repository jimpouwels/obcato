<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateEngine;

class ActionButtonDown extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_down'), $id, 'icon_movedown');
    }

}