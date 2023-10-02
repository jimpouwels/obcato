<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/view/views/action_button.php';

class ActionButtonUp extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_up'), $id, 'icon_moveup');
    }

}