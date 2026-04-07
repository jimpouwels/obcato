<?php

namespace Obcato\Core\elements\list_element\visuals;

use Obcato\Core\view\views\ElementStatic;

class ListElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("list_element/templates/styles/list_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("list_element/templates/scripts/list_element.js.tpl");
        return $scripts;
    }

}