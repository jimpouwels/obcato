<?php
require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonAdd extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_add'), $id, 'icon_add');
    }

}