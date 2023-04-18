<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/settings.php";
    require_once CMS_ROOT . "view/views/module_visual.php";
    require_once CMS_ROOT . "view/views/warning_message.php";
    require_once CMS_ROOT . "database/dao/settings_dao.php";
    require_once CMS_ROOT . "modules/settings/settings_request_handler.php";
    require_once CMS_ROOT . "modules/settings/visuals/global_settings.php";
    require_once CMS_ROOT . "modules/settings/visuals/domain_settings.php";
    require_once CMS_ROOT . "modules/settings/visuals/directory_settings.php";

    class SettingsModuleVisual extends ModuleVisual {

        private static string $TEMPLATE = "settings/root.tpl";
        private static string $HEAD_INCLUDES_TEMPLATE = "modules/settings/head_includes.tpl";
        private Module $_settings_module;
        private Settings $_settings;
        private SettingsRequestHandler $_settings_request_handler;

        public function __construct(Module $settings_module) {
            parent::__construct($settings_module);
            $this->_settings_module = $settings_module;
            $this->_settings = SettingsDao::getInstance()->getSettings();
            $this->_settings_request_handler = new SettingsRequestHandler();
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("warning_message", $this->renderWarningMessage());
            $this->getTemplateEngine()->assign("global_settings_panel", $this->renderGlobalSettingsPanel());
            $this->getTemplateEngine()->assign("directory_settings_panel", $this->renderDirectorySettingsPanel());
            $this->getTemplateEngine()->assign("domain_settings_panel", $this->renderDomainSettingsPanel());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        public function getActionButtons(): array {
            $action_buttons = array();
            $action_buttons[] = new ActionButtonSave('apply_settings');
            return $action_buttons;
        }

        public function renderHeadIncludes(): string {
            $this->getTemplateEngine()->assign("path", $this->_settings_module->getIdentifier());
            return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
        }

        public function getRequestHandlers(): array {
            $request_handlers = array();
            $request_handlers[] = $this->_settings_request_handler;
            return $request_handlers;
        }

        public function onRequestHandled(): void {
        }

        private function renderGlobalSettingsPanel(): string {
            $global_settings_panel = new GlobalSettingsPanel($this->_settings);
            return $global_settings_panel->render();
        }

        private function renderDirectorySettingsPanel(): string {
            $directory_settings_panel = new DirectorySettingsPanel($this->_settings);
            return $directory_settings_panel->render();
        }

        private function renderDomainSettingsPanel(): string {
            $domain_settings_panel = new DomainSettingsPanel($this->_settings);
            return $domain_settings_panel->render();
        }

        private function renderWarningMessage(): string {
            $warning_message = new WarningMessage("Let op! Het incorrect wijzigen van de onderstaande instellingen kan zorgen voor een niet (goed) meer werkende website!");
            return $warning_message->render();
        }

    }

?>
