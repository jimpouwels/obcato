<?php

namespace Pageflow\Core\modules\settings;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\database\dao\SettingsDaoMysql;
use Pageflow\Core\modules\settings\model\Settings;
use Pageflow\Core\modules\settings\visuals\DomainSettingsPanel;
use Pageflow\Core\modules\settings\visuals\FrontendSettingsPanel;
use Pageflow\Core\modules\settings\visuals\GlobalSettingsPanel;
use Pageflow\Core\modules\settings\visuals\SecuritySettingsPanel;
use Pageflow\Core\view\views\ActionButtonSave;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;
use Pageflow\Core\view\views\WarningMessage;

class SettingsModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = "settings/templates/head_includes.tpl";
    private Module $settingsModule;
    private Settings $settings;
    private SettingsRequestHandler $settingsRequestHandler;

    public function __construct(Module $settingsModule) {
        parent::__construct($settingsModule);
        $this->settingsModule = $settingsModule;
        $this->settings = SettingsDaoMysql::getInstance()->getSettings();
        $this->settingsRequestHandler = new SettingsRequestHandler($this->settings);
    }

    public function getTemplateFilename(): string {
        return "settings/templates/root.tpl";
    }

    public function load(): void {
        $this->assign("warning_message", $this->renderWarningMessage());
        $this->assign("global_settings_panel", $this->renderGlobalSettingsPanel());
        $this->assign("domain_settings_panel", $this->renderDomainSettingsPanel());
        $this->assign("frontend_settings_panel", $this->renderFrontendSettingsPanel());
        $this->assign("security_settings_panel", $this->renderSecuritySettingsPanel());
    }

    public function getActionButtons(): array {
        $actionButtons = array();
        $actionButtons[] = new ActionButtonSave('apply_settings');
        return $actionButtons;
    }

    public function renderStyles(): array {
        $styles = array();
        
        // Render module CSS
        $styles[] = $this->getTemplateEngine()->fetch("settings/templates/styles/settings.css.tpl");
        
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        
        // Render module JS
        $scripts[] = $this->getTemplateEngine()->fetch("settings/templates/scripts/module_settings.js.tpl");
        
        return $scripts;
    }

    public function getRequestHandlers(): array {
        $requestHandlers = array();
        $requestHandlers[] = $this->settingsRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }

    private function renderGlobalSettingsPanel(): string {
        return (new GlobalSettingsPanel($this->settings))->render();
    }

    private function renderDomainSettingsPanel(): string {
        return (new DomainSettingsPanel($this->settings))->render();
    }

    private function renderFrontendSettingsPanel(): string {
        return (new FrontendSettingsPanel($this->settings))->render();
    }

    private function renderSecuritySettingsPanel(): string {
        return (new SecuritySettingsPanel($this->settings))->render();
    }

    private function renderWarningMessage(): string {
        return (new WarningMessage("settings_warning_message"))->render();
    }

}
