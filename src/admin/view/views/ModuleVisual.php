<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\ModuleVisual as IModuleVisual;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\core\Blackboard;

abstract class ModuleVisual extends Visual implements IModuleVisual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTitle(): string {
        return $this->getTextResource(Blackboard::$MODULE->getIdentifier() . '_module_title');
    }

    public function getModuleIdentifier(): string {
        return Blackboard::$MODULE->getIdentifier();
    }

}