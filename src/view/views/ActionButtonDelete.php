<?php

namespace Obcato\Core\view\views;

class ActionButtonDelete extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_delete'), $id, 'icon_delete');
    }

}