<?php

namespace Obcato\Core\admin\modules\components;

use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\modules\components\visuals\components\ComponentsTabVisual;
use Obcato\Core\admin\modules\components\visuals\installation\InstallationTabVisual;
use Obcato\Core\admin\view\views\ActionButtonDelete;
use Obcato\Core\admin\view\views\ActionButtonSave;
use Obcato\Core\admin\view\views\ModuleVisual;
use Obcato\Core\admin\view\views\TabMenu;

class ComponentsModuleVisual extends ModuleVisual {

    private static string $HEAD_INCLUDES_TEMPLATE = 'components/head_includes.tpl';
    private static int $COMPONENTS_TAB = 0;
    private static int $INSTALLATION_TAB = 1;
    private InstallRequestHandler $installRequestHandler;
    private ComponentRequestHandler $componentRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
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

    public function loadTabMenu(TabMenu $tabMenu): int {
        $tabMenu->addItem("Componenten", self::$COMPONENTS_TAB);
        $tabMenu->addItem("Installeren", self::$INSTALLATION_TAB);
        return $this->getCurrentTabId();
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