<?php

require_once CMS_ROOT . '/view/views/ModuleVisual.php';
require_once CMS_ROOT . '/view/views/TabMenu.php';
require_once CMS_ROOT . '/modules/components/visuals/installation/InstallationTabVisual.php';
require_once CMS_ROOT . '/modules/components/visuals/components/ComponentsTabVisual.php';
require_once CMS_ROOT . '/modules/components/InstallRequestHandler.php';
require_once CMS_ROOT . '/modules/components/ComponentRequestHandler.php';

class ComponentsModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = 'components/head_includes.tpl';
    private static int $COMPONENTS_TAB = 0;
    private static int $INSTALLATION_TAB = 1;
    private InstallRequestHandler $installRequestHandler;
    private ComponentRequestHandler $componentRequestHandler;

    public function __construct($componentsModule) {
        parent::__construct($componentsModule);
        $this->installRequestHandler = new InstallRequestHandler();
        $this->componentRequestHandler = new ComponentRequestHandler();
    }

    public function getTemplateFilename(): string {
        return 'modules/components/root.tpl';
    }

    public function load(): void {
        if ($this->getCurrentTabId() == self::$COMPONENTS_TAB) {
            $content = new ComponentsTabVisual($this->componentRequestHandler);
        } else {
            $content = new InstallationTabVisual($this->installRequestHandler);
        }
        $this->assign('content', $content->render());
    }

    public function renderHeadIncludes(): string {
        return $this->getTemplateEngine()->fetch("modules/" . self::$HEAD_INCLUDES_TEMPLATE);
    }

    public function getRequestHandlers(): array {
        $request_handlers = array();
        if ($this->getCurrentTabId() == self::$COMPONENTS_TAB)
            $request_handlers[] = $this->componentRequestHandler;
        if ($this->getCurrentTabId() == self::$INSTALLATION_TAB)
            $request_handlers[] = $this->installRequestHandler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getActionButtons(): array {
        $action_buttons = array();
        if ($this->getCurrentTabId() == self::$INSTALLATION_TAB)
            $action_buttons[] = new ActionButtonSave('upload_component');
        if ($this->isCurrentComponentUninstallable())
            $action_buttons[] = new ActionButtonDelete('uninstall_component');
        return $action_buttons;
    }

    public function getTabMenu(): ?TabMenu {
        $tab_menu = new TabMenu($this->getCurrentTabId());
        $tab_menu->addItem("Componenten", self::$COMPONENTS_TAB);
        $tab_menu->addItem("Installeren", self::$INSTALLATION_TAB);
        return $tab_menu;
    }

    private function isCurrentComponentUninstallable(): bool {
        $current_module = $this->componentRequestHandler->getCurrentModule();
        if ($current_module && $current_module->isSystemDefault()) {
            return true;
        }
        $current_element = $this->componentRequestHandler->getCurrentElementType();
        if ($current_module && !$current_element->getSystemDefault()) {
            return true;
        }
        return false;
    }
}