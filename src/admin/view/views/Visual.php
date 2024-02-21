<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual as IVisual;
use Obcato\Core\admin\authentication\Session;
use Obcato\Core\admin\core\Blackboard;

abstract class Visual extends IVisual {

    public function __construct(TemplateEngine $templateEngine, ?Visual $parent = null) {
        parent::__construct($templateEngine, $parent);
    }

    public function render(): string {
        $this->load();
        return $this->getTemplateEngine()->fetch($this->getTemplateFilename(), $this->getTemplateData());
    }

    protected function assign(string $key, mixed $value): void {
        $this->getTemplateEngine()->assign($key, $value);
    }

    protected function assignGlobal(string $key, mixed $value): void {
        $this->getTemplateEngine()->assign($key, $value);
    }

    protected function createChildData(): TemplateData {
        return $this->getTemplateEngine()->createChildData();
    }

    protected function fetch(string $template, TemplateData $data): string {
        return $this->getTemplateEngine()->fetch($template, $data);
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