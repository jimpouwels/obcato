<?php

namespace Pageflow\Core;

use Pageflow\Core\authentication\Session;
use Pageflow\Core\request_handlers\BackendRequestHandler;
use Pageflow\Core\view\views\Cms;
use Pageflow\Core\view\views\ModuleVisual;
use const Pageflow\CMS_ROOT;

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
            $class = "Pageflow\\Core\\modules\\" . $currentModule->getIdentifier() . "\\" . $currentModule->getClass();
            $this->moduleVisual = new $class($currentModule);
        }
    }

    private function renderCms(): void {
        $this->renderCmsView();
    }

    private function renderCmsView(): void {
        $cms = new Cms($this->moduleVisual);
        echo $cms->render();
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
            $textResourceLoader = new TextResourceLoader(Session::getCurrentLanguage());
            $text_resources = $textResourceLoader->loadTextResources();
            Session::setTextResources($text_resources);
        }
    }

}