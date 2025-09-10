<?php

namespace Obcato\Core\modules\webforms;

use Obcato\Core\core\model\Module;
use Obcato\Core\modules\webforms\visuals\webforms\WebformTab;
use Obcato\Core\view\views\ActionButtonAdd;
use Obcato\Core\view\views\ActionButtonDelete;
use Obcato\Core\view\views\ActionButtonSave;
use Obcato\Core\view\views\ModuleVisual;
use Obcato\Core\view\views\TabMenu;


class WebFormsModuleVisual extends ModuleVisual {

    private static int $FORMS_TAB = 0;
    private WebformRequestHandler $webformRequestHandler;
    private int $currentTabId = 0;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->webformRequestHandler = new WebformRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "webforms/templates/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->currentTabId == self::$FORMS_TAB) {
            $content = new WebformTab($this->webformRequestHandler);
        }
        $this->assign("content", $content->render());
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->currentTabId == self::$FORMS_TAB) {
            $save_button = null;
            $delete_button = null;
            if (!is_null($this->webformRequestHandler->getCurrentWebForm())) {
                $save_button = new ActionButtonSave('update_webform');
                $delete_button = new ActionButtonDelete('delete_webform');
            }
            $action_buttons[] = $save_button;
            $action_buttons[] = new ActionButtonAdd('add_webform');
            $action_buttons[] = $delete_button;
        }
        return $action_buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->getModuleIdentifier());
        return $this->getTemplateEngine()->fetch("webforms/templates/head_includes.tpl");
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->webformRequestHandler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("webforms_tab_forms", self::$FORMS_TAB);
        return $this->getCurrentTabId();
    }

}