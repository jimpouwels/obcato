<?php
require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonSave extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_save'), $id, 'icon_apply');
    }

}