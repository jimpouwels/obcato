<?php

namespace Pageflow\Core\modules\logout;

use Pageflow\Core\core\model\Module;
use Pageflow\Core\view\views\ModuleVisual;
use Pageflow\Core\view\views\TabMenu;


class LogoutModuleVisual extends ModuleVisual {

    private LogoutRequestHandler $logoutRequestHandler;

    public function __construct(Module $module) {
        parent::__construct($module);
        $this->logoutRequestHandler = new LogoutRequestHandler();
    }

    public function getTemplateFilename(): string {
        return "";
    }

    public function load(): void {}

    public function getActionButtons(): array {
        return array();
    }

    public function renderHeadIncludes(): string {
        return "";
    }

    public function getRequestHandlers(): array {
        return array($this->logoutRequestHandler);
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }

}