<?php

use Obcato\ComponentApi\ModuleVisual;
use Obcato\ComponentApi\Session;

require_once CMS_ROOT . "/request_handlers/BackendRequestHandler.php";
require_once CMS_ROOT . "/view/views/Cms.php";
require_once CMS_ROOT . "/view/views/Popup.php";
require_once CMS_ROOT . "/text_resource_loader.php";

class Backend {

    private BackendRequestHandler $backendRequestHandler;
    private ?ModuleVisual $moduleVisual = null;

    public function __construct() {
        $this->backendRequestHandler = new BackendRequestHandler();
    }

    public function start(): void {
        Session::clearErrors();
        $this->loadTextResources();
        $this->backendRequestHandler->handle();
        $this->loadCurrentModule();
        $this->runModuleRequestHandler();
        $this->renderCms();
    }

    private function loadCurrentModule(): void {
        $currentModule = $this->backendRequestHandler->getCurrentModule();
        if ($currentModule) {
            require_once CMS_ROOT . "/modules/" . $currentModule->getIdentifier() . "/activator.php";
            $class = $currentModule->getClass();
            $this->moduleVisual = new $class(TemplateEngine::getInstance(), $currentModule, MysqlConnector::getInstance(), TemplateEngine::getInstance());
        }
    }

    private function renderCms(): void {
        if ($this->isPopupView()) {
            $this->renderPopupView();
        } else {
            $this->renderCmsView();
        }
    }

    private function renderCmsView(): void {
        $cms = new Cms(TemplateEngine::getInstance(), $this->moduleVisual);
        echo $cms->render();
    }

    private function renderPopupView(): void {
        $popup = new Popup(TemplateEngine::getInstance(), $_GET['popup']);
        echo $popup->render();
    }

    private function runModuleRequestHandler(): void {
        if ($this->moduleVisual) {
            foreach ($this->moduleVisual->getRequestHandlers() as $request_handler) {
                $request_handler->handle();
            }
            $this->moduleVisual->onRequestHandled();
        }
    }

    private function loadTextResources(): void {
        if (!Session::areTextResourcesLoaded()) {
            $text_resource_loader = new TextResourceLoader(Session::getCurrentLanguage());
            $text_resources = $text_resource_loader->loadTextResources();
            Session::setTextResources($text_resources);
        }
    }

    private function isPopupView(): bool {
        return isset($_GET['popup']);
    }

}