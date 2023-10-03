<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/Settings.php";
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/view/views/WarningMessage.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/modules/settings/SettingsRequestHandler.php";
require_once CMS_ROOT . "/modules/settings/visuals/GlobalSettingsPanel.php";
require_once CMS_ROOT . "/modules/settings/visuals/DomainSettingsPanel.php";
require_once CMS_ROOT . "/modules/settings/visuals/DirectorySettingsPanel.php";

class SettingsModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/settings/head_includes.tpl";
    private Module $_settings_module;
    private Settings $_settings;
    private SettingsRequestHandler $_settings_request_handler;

    public function __construct(Module $settings_module) {
        parent::__construct($settings_module);
        $this->_settings_module = $settings_module;
        $this->_settings = SettingsDaoMysql::getInstance()->getSettings();
        $this->_settings_request_handler = new SettingsRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/settings/root.tpl";
    }

    public function load(): void {
        $this->assign("warning_message", $this->renderWarningMessage());
        $this->assign("global_settings_panel", $this->renderGlobalSettingsPanel());
        $this->assign("directory_settings_panel", $this->renderDirectorySettingsPanel());
        $this->assign("domain_settings_panel", $this->renderDomainSettingsPanel());
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

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        return null;
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
        $warning_message = new WarningMessage("settings_warning_message");
        return $warning_message->render();
    }

}

?>
