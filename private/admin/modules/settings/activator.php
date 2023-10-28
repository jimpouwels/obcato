<?php
require_once CMS_ROOT . "/modules/settings/model/Settings.php";
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/view/views/WarningMessage.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";
require_once CMS_ROOT . "/modules/settings/SettingsRequestHandler.php";
require_once CMS_ROOT . "/modules/settings/visuals/GlobalSettingsPanel.php";
require_once CMS_ROOT . "/modules/settings/visuals/DomainSettingsPanel.php";

class SettingsModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "modules/settings/head_includes.tpl";
    private Module $settingsModule;
    private Settings $settings;
    private SettingsRequestHandler $settingsRequestHandler;

    public function __construct(Module $settingsModule) {
        parent::__construct($settingsModule);
        $this->settingsModule = $settingsModule;
        $this->settings = SettingsDaoMysql::getInstance()->getSettings();
        $this->settingsRequestHandler = new SettingsRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "modules/settings/root.tpl";
    }

    public function load(): void {
        $this->assign("warning_message", $this->renderWarningMessage());
        $this->assign("global_settings_panel", $this->renderGlobalSettingsPanel());
        $this->assign("domain_settings_panel", $this->renderDomainSettingsPanel());
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        $actionButtons[] = new ActionButtonSave('apply_settings');
        return $actionButtons;
    }

    public function renderHeadIncludes(): string {
        $this->getTemplateEngine()->assign("path", $this->settingsModule->getIdentifier());
        return $this->getTemplateEngine()->fetch(self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->settingsRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        return null;
    }

    private function renderGlobalSettingsPanel(): string {
        return (new GlobalSettingsPanel($this->settings))->render();
    }

    private function renderDomainSettingsPanel(): string {
        return (new DomainSettingsPanel($this->settings))->render();
    }

    private function renderWarningMessage(): string {
        return (new WarningMessage("settings_warning_message"))->render();
    }

}
