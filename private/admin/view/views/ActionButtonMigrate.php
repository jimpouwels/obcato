<?php
require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonMigrate extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_migrate'), $id, 'icon_migrate');
    }

}