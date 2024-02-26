<?php

namespace Obcato\Core\view\views;

class ActionButtonReload extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_reload'), $id, 'icon_reload');
    }

}