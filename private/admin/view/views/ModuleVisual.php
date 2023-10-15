<?php

abstract class ModuleVisual extends Visual {

    private Module $module;

    protected function __construct(Module $module) {
        parent::__construct();
        $this->module = $module;
    }

    public function getTitle(): string {
        return $this->getTextResource($this->module->getTitleTextResourceIdentifier());
    }

    abstract function getActionButtons(): array;

    abstract function renderHeadIncludes(): string;

    abstract function getTabMenu(): ?TabMenu;

    abstract function getRequestHandlers(): array;

    public function onRequestHandled(): void {}

    public function getModuleIdentifier(): string {
        return $this->module->getIdentifier();
    }

}