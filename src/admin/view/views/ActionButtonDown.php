<?php

namespace Obcato\Core\admin\view\views;

class ActionButtonDown extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_down'), $id, 'icon_movedown');
    }

}