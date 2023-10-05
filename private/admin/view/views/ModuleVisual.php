<?php

abstract class ModuleVisual extends Visual {

    private Module $_module;

    protected function __construct(Module $module) {
        parent::__construct();
        $this->_module = $module;
    }

    public function getTitle(): string {
        return $this->getTextResource($this->_module->getTitleTextResourceIdentifier());
    }

    abstract function getActionButtons(): array;

    abstract function renderHeadIncludes(): string;

    abstract function getTabMenu(): ?TabMenu;

    abstract function getRequestHandlers(): array;

    public function onRequestHandled(): void {}

}