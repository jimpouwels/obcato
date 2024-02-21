<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\authentication\Session;
use Obcato\Core\admin\core\Blackboard;
use Obcato\Core\admin\view\TemplateEngine;

abstract class Visual implements \Obcato\ComponentApi\Visual {

    private TemplateEngine $templateEngine;
    private TemplateData $templateData;

    public function __construct() {
        $this->templateEngine = TemplateEngine::getInstance();
        $this->templateData = $this->templateEngine->createChildData();
    }

    public function render(): string {
        $this->load();
        return $this->templateEngine->fetch($this->getTemplateFilename(), $this->templateData);
    }

    public function getTemplateEngine(): TemplateEngine {
        return $this->templateEngine;
    }

    public function getTemplateData(): TemplateData {
        return $this->templateData;
    }

    public function assign(string $key, mixed $value): void {
        $this->templateEngine->assign($key, $value);
    }

    public function assignGlobal(string $key, mixed $value): void {
        $this->templateEngine->assign($key, $value);
    }

    public function createChildData(): TemplateData {
        return $this->templateEngine->createChildData();
    }

    public function fetch(string $template, TemplateData $data): string {
        return $this->templateEngine->fetch($template, $data);
    }

    public function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }

    public function getBackendBaseUrl(): string {
        return BlackBoard::getBackendBaseUrl();
    }

    public function getBackendBaseUrlRaw(): string {
        return BlackBoard::getBackendBaseUrlRaw();
    }

    public function getBackendBaseUrlWithoutTab(): string {
        return BlackBoard::getBackendBaseUrlWithoutTab();
    }

    public function getCurrentTabId(): int {
        return BlackBoard::$MODULE_TAB_ID;
    }
}