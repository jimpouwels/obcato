<?php

namespace Obcato\Core\admin\modules\logout;

use Obcato\ComponentApi\ModuleVisual;
use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\core\model\Module;


class LogoutModuleVisual extends ModuleVisual {

    private LogoutRequestHandler $logoutRequestHandler;

    public function __construct(TemplateEngine $templateEngine, Module $module) {
        parent::__construct($templateEngine, $module);
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
        $requestHandlers = array();
        $requestHandlers[] = $this->logoutRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function loadTabMenu(TabMenu $tabMenu): int {
        return $this->getCurrentTabId();
    }

}