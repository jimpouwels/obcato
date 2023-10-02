<?php
defined('_ACCESS') or die;

class ActionButton extends Visual {

    private string $_label;
    private string $_action_id;
    private string $_icon_class;

    public function __construct(string $label, string $action_id, string $icon_class) {
        parent::__construct();
        $this->_label = $label;
        $this->_action_id = $action_id;
        $this->_icon_class = $icon_class;
    }

    public function getTemplateFilename(): string {
        return "system/actions_menu_button.tpl";
    }

    public function load(): void {
        $this->assign("action_id", $this->_action_id);
        $this->assign("icon_class", $this->_icon_class);
        $this->assign("label", $this->_label);
    }

}
