<?php
require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/logout/LogoutRequestHandler.php";

class LogoutModuleVisual extends ModuleVisual {

    private LogoutRequestHandler $logoutRequestHandler;

    public function __construct($module) {
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
        $requestHandlers = array();
        $requestHandlers[] = $this->logoutRequestHandler;
        return $requestHandlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        return null;
    }

}