<?php

namespace Pageflow\Core\elements\form_element\visuals;

use Pageflow\Core\view\views\ElementStatic;

class FormElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("form_element/templates/styles/form_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}