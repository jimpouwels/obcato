<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/settings.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "view/views/warning_message.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "modules/settings/settings_pre_handler.php";
    require_once CMS_ROOT . "modules/settings/visuals/global_settings.php";
    require_once CMS_ROOT . "modules/settings/visuals/domain_settings.php";
    require_once CMS_ROOT . "modules/settings/visuals/directory_settings.php";

    class SettingsModuleVisual extends ModuleVisual {

        private static $TEMPLATE = "settings/root.tpl";
        private static $HEAD_INCLUDES_TEMPLATE = "modules/settings/head_includes.tpl";
        private $_template_engine;
        private $_settings_module;
        private $_settings;
        private $_settings_pre_handler;

        public function __construct($settings_module) {
            parent::__construct($settings_module);
            $this->_settings_module = $settings_module;
            $this->_settings = SettingsDao::getInstance()->getSettings();
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_settings_pre_handler = new SettingsPreHandler();
        }

        public function renderVisual(): string {
            $this->_template_engine->assign("warning_message", $this->renderWarningMessage());
            $this->_template_engine->assign("global_settings_panel", $this->renderGlobalSettingsPanel());
            $this->_template_engine->assign("directory_settings_panel", $this->renderDirectorySettingsPanel());
            $this->_template_engine->assign("domain_settings_panel", $this->renderDomainSettingsPanel());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        public function getActionButtons() {
            $action_buttons = array();
            $action_buttons[] = new ActionButtonSave('apply_settings');
            return $action_buttons;
        }

        public function getHeadIncludes() {
            $this->_template_engine->assign("path", $this->_settings_module->getIdentifier());
            return $this->_template_engine->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers() {
            $request_handlers = array();
            $request_handlers[] = $this->_settings_pre_handler;
            return $request_handlers;
        }

        public function onPreHandled() {
        }

        private function renderGlobalSettingsPanel() {
            $global_settings_panel = new GlobalSettingsPanel($this->_settings);
            return $global_settings_panel->render();
        }

        private function renderDirectorySettingsPanel() {
            $directory_settings_panel = new DirectorySettingsPanel($this->_settings);
            return $directory_settings_panel->render();
        }

        private function renderDomainSettingsPanel() {
            $domain_settings_panel = new DomainSettingsPanel($this->_settings);
            return $domain_settings_panel->render();
        }

        private function renderWarningMessage() {
            $warning_message = new WarningMessage("Let op! Het incorrect wijzigen van de onderstaande instellingen kan zorgen voor een niet (goed) meer werkende website!");
            return $warning_message->render();
        }

    }

?>
