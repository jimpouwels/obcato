<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\ModuleVisual;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\authentication\Session;
use Obcato\Core\admin\core\Blackboard;
use Obcato\Core\admin\database\dao\ModuleDao;
use Obcato\Core\admin\database\dao\ModuleDaoMysql;
use Obcato\Core\admin\database\dao\SettingsDaoMysql;
use Obcato\Core\admin\modules\settings\model\Settings;
use Obcato\Core\admin\utilities\Logs;
use const Obcato\Core\admin\SYSTEM_VERSION;

class Cms extends Visual {
    private ?ModuleVisual $moduleVisual;
    private Settings $settings;
    private ModuleDao $moduleDao;

    public function __construct(TemplateEngine $templateEngine, ?ModuleVisual $moduleVisual) {
        parent::__construct($templateEngine);
        $this->moduleDao = ModuleDaoMysql::getInstance();
        $this->moduleVisual = $moduleVisual;
        $this->settings = SettingsDaoMysql::getInstance()->getSettings();
    }

    public function getTemplateFilename(): string {
        return "system/cms.tpl";
    }

    public function load(): void {
        $navigation_menu = new NavigationMenu($this->getTemplateEngine(), $this->moduleDao->getModuleGroups());
        $notification_bar = new NotificationBar($this->getTemplateEngine());
        $current_user_indicator = new CurrentUserIndicator($this->getTemplateEngine());

        $this->assignGlobal("text_resources", Session::getTextResources());

        if ($this->moduleVisual) {
            $this->assignGlobal("page_title", $this->moduleVisual->getTitle());
            $this->assignGlobal("module_head_includes", $this->moduleVisual->renderHeadIncludes());
        }
        $this->assignGlobal("backend_base_url", $this->getBackendBaseUrl());
        $this->assignGlobal("backend_base_url_raw", $this->getBackendBaseUrlRaw());
        $this->assignGlobal("backend_base_url_without_tab", $this->getBackendBaseUrlWithoutTab());

        $module_id_text_field = new TextField($this->getTemplateEngine(), "module_id", "", BlackBoard::$MODULE_ID, true, false, "", false);
        $this->assignGlobal("module_id_form_field", $module_id_text_field->render());
        $module_tab_id_text_field = new TextField($this->getTemplateEngine(), "module_tab_id", "", BlackBoard::$MODULE_TAB_ID, true, false, "", false);
        $this->assignGlobal("module_tab_id_form_field", $module_tab_id_text_field->render());

        $this->assignGlobal("actions_menu", $this->getActionsMenu()->render());
        $this->assignGlobal("website_title", $this->settings->getWebsiteTitle());
        $this->assignGlobal("navigation_menu", $navigation_menu->render());
        $this->assignGlobal("current_user_indicator", $current_user_indicator->render());
        $this->assignGlobal("notification_bar", $notification_bar->render());
        $this->assignGlobal("content_pane", $this->renderContentPane());
        $this->assignGlobal("tab_menu", $this->renderTabMenu());
        $this->assignGlobal("system_version", SYSTEM_VERSION);
        $this->assignGlobal("db_version", $this->settings->getDatabaseVersion());

        if (Logs::hasLogs()) {
            $system_logs = new WarningMessage($this->getTemplateEngine(), Logs::asString());
            $this->assign('system_logs', $system_logs->render());
        }
    }

    private function getActionsMenu(): ActionsMenu {
        $action_buttons = array();
        if ($this->moduleVisual) {
            $action_buttons = $this->moduleVisual->getActionButtons();
        }
        return new ActionsMenu($this->getTemplateEngine(), $action_buttons);
    }

    private function renderContentPane(): string {
        if (!is_null($this->moduleVisual)) {
            return $this->moduleVisual->render();
        } else {
            return $this->getTemplateEngine()->fetch("system/home_wrapper.tpl");
        }
    }

    private function renderTabMenu(): string {
        if (!is_null($this->moduleVisual)) {
            $tabMenu = new TabMenu($this->getTemplateEngine(), 0);
            $activeTabId = $this->moduleVisual->loadTabMenu($tabMenu);
            $tabMenu->setCurrentTabId($activeTabId);
            return $tabMenu->render();

        }
        return "";
    }

}