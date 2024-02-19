<?php
require_once CMS_ROOT . '/view/views/ActionButton.php';

class ActionButtonDown extends ActionButton {

    public function __construct(TemplateEngine $templateEngine, string $id) {
        parent::__construct($templateEngine, $this->getTextResource('action_button_down'), $id, 'icon_movedown');
    }

}