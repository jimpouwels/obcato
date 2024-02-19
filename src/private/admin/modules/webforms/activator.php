<?php

use Obcato\ComponentApi\ModuleVisual;

require_once CMS_ROOT . "/view/views/TabMenu.php";
require_once CMS_ROOT . "/modules/webforms/WebformRequestHandler.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/WebformTab.php";
require_once CMS_ROOT . "/database/dao/WebformDaoMysql.php";

class WebFormsModuleVisual extends ModuleVisual {

    private static int $FORMS_TAB = 0;
    private WebformRequestHandler $webformRequestHandler;
    private int $currentTabId = 0;

    public function __construct(TemplateEngine $templateEngine, Module $module) {
        parent::__construct($templateEngine, $module);
        $this->webformRequestHandler = new WebformRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/webforms/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->currentTabId == self::$FORMS_TAB) {
            $content = new WebformTab($this->getTemplateEngine(), $this->webformRequestHandler);
        }
        $this->assign("content", $content->render());
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->currentTabId == self::$FORMS_TAB) {
            $save_button = null;
            $delete_button = null;
            if (!is_null($this->webformRequestHandler->getCurrentWebForm())) {
                $save_button = new ActionButtonSave($this->getTemplateEngine(), 'update_webform');
                $delete_button = new ActionButtonDelete($this->getTemplateEngine(), 'delete_webform');
            }
            $action_buttons[] = $save_button;
            $action_buttons[] = new ActionButtonAdd($this->getTemplateEngine(), 'add_webform');
            $action_buttons[] = $delete_button;
        }
        return $action_buttons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->getModuleIdentifier());
        return $this->getTemplateEngine()->fetch("modules/webforms/head_includes.tpl");
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->webformRequestHandler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getTemplateEngine(), $this->getCurrentTabId());
        $tab_menu->addItem("webforms_tab_forms", self::$FORMS_TAB);
        return $tab_menu;
    }

}