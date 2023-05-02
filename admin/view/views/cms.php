<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";
    require_once CMS_ROOT . "view/views/navigation_menu.php";
    require_once CMS_ROOT . "view/views/current_user_indicator.php";
    require_once CMS_ROOT . "view/views/actions_menu.php";
    require_once CMS_ROOT . "view/views/notification_bar.php";

    class Cms extends Visual {
        private ?ModuleVisual $_module_visual = null;
        private string $_website_title;
        private ModuleDao $_module_dao;

        public function __construct(?ModuleVisual $module_visual, string $website_title) {
            parent::__construct();
            $this->_module_dao = ModuleDao::getInstance();
            $this->_module_visual = $module_visual;
            $this->_website_title = $website_title;
        }

        public function getTemplateFilename(): string {
            return "system/cms.tpl";
        }

        public function load(): void {
            $navigation_menu = new NavigationMenu($this->_module_dao->getModuleGroups());
            $notification_bar = new NotificationBar();
            $current_user_indicator = new CurrentUserIndicator();

            $this->assignGlobal("text_resources", Session::getTextResources());

            if (!is_null($this->_module_visual)) {
                $this->assignGlobal("page_title", $this->_module_visual->getTitle());
                $this->assignGlobal("module_head_includes", $this->_module_visual->renderHeadIncludes());
            }
            $this->assignGlobal("backend_base_url", $this->getBackendBaseUrl());
            $this->assignGlobal("backend_base_url_raw", $this->getBackendBaseUrlRaw());
            $this->assignGlobal("backend_base_url_without_tab", $this->getBackendBaseUrlWithoutTab());

            $module_id_text_field = new TextField("module_id", "", BlackBoard::$MODULE_ID, true, false, "", false);
            $this->assignGlobal("module_id_form_field", $module_id_text_field->render());
            $module_tab_id_text_field = new TextField("module_tab_id", "", BlackBoard::$MODULE_TAB_ID, true, false, "", false);
            $this->assignGlobal("module_tab_id_form_field", $module_tab_id_text_field->render());

            $this->assignGlobal("actions_menu", $this->getActionsMenu()->render());
            $this->assignGlobal("website_title", $this->_website_title);
            $this->assignGlobal("navigation_menu", $navigation_menu->render());
            $this->assignGlobal("current_user_indicator", $current_user_indicator->render());
            $this->assignGlobal("notification_bar", $notification_bar->render());
            $this->assignGlobal("content_pane", $this->renderContentPane());
            $this->assignGlobal("tab_menu", $this->renderTabMenu());
            $this->assignGlobal("system_version", SYSTEM_VERSION);
            $this->assignGlobal("db_version", DB_VERSION);
        }

        private function getActionsMenu(): ActionsMenu {
            $action_buttons = array();
            if (!is_null($this->_module_visual)) {
                $action_buttons = $this->_module_visual->getActionButtons();
            }
            return new ActionsMenu($action_buttons);
        }

        private function renderContentPane(): string {
            if (!is_null($this->_module_visual)) {
                return $this->_module_visual->render();
            } else {
                return $this->getTemplateEngine()->fetch("system/home_wrapper.tpl");
            }
        }

        private function renderTabMenu(): string {
            if (!is_null($this->_module_visual)) {
                $tab_menu = $this->_module_visual->getTabMenu();
                return $tab_menu ? $tab_menu->render() : "";

            }
            return "";
        }

    }
