<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . 'view/views/action_button.php';

class ActionButtonSave extends ActionButton {

    public function __construct(string $id) {
        parent::__construct($this->getTextResource('action_button_save'), $id, 'icon_apply');
    }

}