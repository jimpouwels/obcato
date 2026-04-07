<?php

namespace Obcato\Core\elements\separator_element\visuals;

use Obcato\Core\view\views\ElementStatic;

class SeparatorElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("separator_element/templates/styles/separator_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}