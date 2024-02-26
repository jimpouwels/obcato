<?php

namespace Obcato\Core\view\views;

class ActionButtonSave extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_save'), $id, 'icon_apply');
    }

}