<?php

namespace Pageflow\Core\view\views;

class ActionButtonAddFolder extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_add_folder'), $id, 'icon_add_folder');
    }

}
