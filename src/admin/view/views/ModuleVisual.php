<?php

namespace Obcato\Core\admin\view\views;

use Obcato\Core\admin\core\Blackboard;

abstract class ModuleVisual extends Visual implements \Obcato\ComponentApi\ModuleVisual {

    public function __construct() {
        parent::__construct();
    }

    public function getTitle(): string {
        return $this->getTextResource(Blackboard::$MODULE->getIdentifier() . '_module_title');
    }

    public function getModuleIdentifier(): string {
        return Blackboard::$MODULE->getIdentifier();
    }

}