<?php

namespace Obcato\Core\view\views;

class ActionsMenu extends Visual {

    private array $actionButtons;

    public function __construct(array $actionButtons) {
        parent::__construct();
        $this->actionButtons = $actionButtons;
    }

    public function getTemplateFilename(): string {
        return "system/actions_menu.tpl";
    }

    public function load(): void {
        $this->assign("buttons", $this->getActionButtonsHtml());
    }

    private function getActionButtonsHtml(): array {
        $buttonsHtml = array();
        foreach ($this->actionButtons as $actionButton) {
            if (!is_null($actionButton)) {
                $buttonsHtml[] = $actionButton->render();
            }
        }
        return $buttonsHtml;
    }

}