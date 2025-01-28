<?php

namespace Obcato\Core\view\views;

class ActionButton extends Visual {

    private string $label;
    private string $actionId;
    private string $iconClass;

    public function __construct(string $label, string $actionId, string $iconClass) {
        parent::__construct();
        $this->label = $label;
        $this->actionId = $actionId;
        $this->iconClass = $iconClass;
    }

    public function getTemplateFilename(): string {
        return "system/actions_menu_button.tpl";
    }

    public function load(): void {
        $this->assign("action_id", $this->actionId);
        $this->assign("icon_class", $this->iconClass);
        $this->assign("label", $this->label);
    }

}
