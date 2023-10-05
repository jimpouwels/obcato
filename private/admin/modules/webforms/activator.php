<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/webforms/WebformRequestHandler.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/WebformTab.php";
require_once CMS_ROOT . "/database/dao/WebformDaoMysql.php";
require_once CMS_ROOT . "/view/views/TabMenu.php";

class WebFormsModuleVisual extends ModuleVisual {

    private static int $FORMS_TAB = 0;
    private WebformRequestHandler $_webform_request_handler;
    private Module $_webform_module;
    private int $_current_tab_id = 0;

    public function __construct(Module $form_module) {
        parent::__construct($form_module);
        $this->_webform_module = $form_module;
        $this->_webform_request_handler = new WebformRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/webforms/root.tpl";
    }

    public function load(): void {
        $content = null;
        if ($this->_current_tab_id == self::$FORMS_TAB) {
            $content = new WebformTab($this->_webform_request_handler);
        }
        $this->assign("content", $content->render());
    }

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->_current_tab_id == self::$FORMS_TAB) {
            $save_button = null;
            $delete_button = null;
            if (!is_null($this->_webform_request_handler->getCurrentWebForm())) {
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
        $this->getTemplateEngine()->assign("path", $this->_webform_module->getIdentifier());
        return $this->getTemplateEngine()->fetch("modules/webforms/head_includes.tpl");
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        $request_handlers[] = $this->_webform_request_handler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getCurrentTabId());
        $tab_menu->addItem("webforms_tab_forms", self::$FORMS_TAB);
        return $tab_menu;
    }

}

?>