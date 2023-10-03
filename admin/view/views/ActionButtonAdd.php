<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonAdd extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_add'), $id, 'icon_add');
    }

}