<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonDown extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_down'), $id, 'icon_movedown');
    }

}