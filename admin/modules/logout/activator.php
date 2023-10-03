<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/ModuleVisual.php";
require_once CMS_ROOT . "/modules/logout/LogoutRequestHandler.php";

class LogoutModuleVisual extends ModuleVisual {

    private LogoutRequestHandler $_logout_request_handler;

    public function __construct($module) {
        parent::__construct($module);
        $this->_logout_request_handler = new LogoutRequestHandler();
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
        $request_handlers = array();
        $request_handlers[] = $this->_logout_request_handler;
        return $request_handlers;
    }

    public function onRequestHandled(): void {}

    public function getTabMenu(): ?TabMenu {
        return null;
    }

}

?>