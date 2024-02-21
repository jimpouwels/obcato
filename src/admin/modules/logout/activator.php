<?php

namespace Obcato\Core\admin\modules\logout;

use Obcato\ComponentApi\TabMenu;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\ModuleVisual;


class LogoutModuleVisual extends ModuleVisual {

    private LogoutRequestHandler $logoutRequestHandler;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
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