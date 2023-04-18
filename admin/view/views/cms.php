<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "authentication/session.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";
    require_once CMS_ROOT . "view/views/navigation_menu.php";
    require_once CMS_ROOT . "view/views/current_user_indicator.php";
    require_once CMS_ROOT . "view/views/actions_menu.php";
    require_once CMS_ROOT . "view/views/notification_bar.php";

    class Cms extends Visual {
        private static $TEMPLATE = "system/cms.tpl";
        private $_module_visual;
        private $_website_title;
        private $_module_dao;

        public function __construct($module_visual, $website_title) {
            parent::__construct();
            $this->_module_dao = ModuleDao::getInstance();
            $this->_module_visual = $module_visual;
            $this->_website_title = $website_title;
        }

        public function render(): string {

            $navigation_menu = new NavigationMenu($this->_module_dao->getModuleGroups());
            $notification_bar = new NotificationBar();
            $current_user_indicator = new CurrentUserIndicator();

            $this->getTemplateEngine()->assign("text_resources", Session::getTextResources());

            if (!is_null($this->_module_visual)) {
                $this->getTemplateEngine()->assign("page_title", $this->_module_visual->getTitle());
                $this->getTemplateEngine()->assign("module_head_includes", $this->_module_visual->renderHeadIncludes());
            }
            $this->getTemplateEngine()->assign("backend_base_url", $this->getBackendBaseUrl());
            $this->getTemplateEngine()->assign("backend_base_url_raw", $this->getBackendBaseUrlRaw());
            $this->getTemplateEngine()->assign("backend_base_url_without_tab", $this->getBackendBaseUrlWithoutTab());

            $module_id_text_field = new TextField("module_id", "", BlackBoard::$MODULE_ID, true, false, "", false);
            $this->getTemplateEngine()->assign("module_id_form_field", $module_id_text_field->render());
            $module_tab_id_text_field = new TextField("module_tab_id", "", BlackBoard::$MODULE_TAB_ID, true, false, "", false);
            $this->getTemplateEngine()->assign("module_tab_id_form_field", $module_tab_id_text_field->render());

            $this->getTemplateEngine()->assign("actions_menu", $this->getActionsMenu()->render());
            $this->getTemplateEngine()->assign("website_title", $this->_website_title);
            $this->getTemplateEngine()->assign("navigation_menu", $navigation_menu->render());
            $this->getTemplateEngine()->assign("current_user_indicator", $current_user_indicator->render());
            $this->getTemplateEngine()->assign("notification_bar", $notification_bar->render());
            $this->getTemplateEngine()->assign("content_pane", $this->renderContentPane());
            $this->getTemplateEngine()->assign("system_version", SYSTEM_VERSION);
            $this->getTemplateEngine()->assign("db_version", DB_VERSION);

            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
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
                return $this->_module_visual->render($this);
            } else {
                return $this->getTemplateEngine()->fetch("system/home_wrapper.tpl");
            }
        }

    }
