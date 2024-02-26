<?php

namespace Obcato\Core\view\views;

use Obcato\Core\authentication\Session;
use Obcato\Core\core\BlackBoard;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\TemplateEngine;

abstract class Visual {

    private TemplateEngine $templateEngine;
    private TemplateData $templateData;

    public function __construct(?Visual $parent = null) {
        $this->templateEngine = TemplateEngine::getInstance();
        $this->templateData = $this->templateEngine->createChildData();
    }

    public function render(): string {
        $this->load();
        return $this->templateEngine->fetch($this->getTemplateFilename(), $this->templateData);
    }

    abstract function load(): void;

    abstract function getTemplateFilename(): string;

    protected function getTemplateEngine(): TemplateEngine {
        return $this->templateEngine;
    }

    protected function assign(string $key, mixed $value): void {
        $this->templateData->assign($key, $value);
    }

    protected function assignGlobal(string $key, mixed $value): void {
        $this->templateEngine->assign($key, $value);
    }

    protected function createChildData(): TemplateData {
        return $this->templateEngine->createChildData();
    }

    protected function fetch(string $template, TemplateData $data): string {
        return $this->templateEngine->fetch($template, $data);
    }

    protected function getTextResource(string $identifier): string {
        return Session::getTextResource($identifier);
    }

    protected function getBackendBaseUrl(): string {
        return BlackBoard::getBackendBaseUrl();
    }

    protected function getBackendBaseUrlRaw(): string {
        return BlackBoard::getBackendBaseUrlRaw();
    }

    protected function getBackendBaseUrlWithoutTab(): string {
        return BlackBoard::getBackendBaseUrlWithoutTab();
    }

    protected function getCurrentTabId(): int {
        return BlackBoard::$MODULE_TAB_ID;
    }
}