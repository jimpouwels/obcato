<?php

namespace Obcato\Core\admin\view\views;

class ActionButtonAdd extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_add'), $id, 'icon_add');
    }

}