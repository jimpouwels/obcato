<?php

namespace Obcato\Core\admin\view\views;

class ActionButtonUp extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_up'), $id, 'icon_moveup');
    }

}