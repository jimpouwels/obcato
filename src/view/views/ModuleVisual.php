<?php

namespace Obcato\Core\view\views;

use Obcato\Core\core\model\Module;

abstract class ModuleVisual extends Visual {

    private Module $module;

    protected function __construct(Module $module) {
        parent::__construct();
        $this->module = $module;
    }

    public function getTitle(): string {
        return $this->getTextResource($this->module->getIdentifier() . '_module_title');
    }

    public function getModuleIdentifier(): string {
        return $this->module->getIdentifier();
    }

    abstract function loadTabMenu(TabMenu $tabMenu): int;

}