<?php

namespace Pageflow\Core\elements\text_element\visuals;

use Pageflow\Core\view\views\ElementStatic;

class TextElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("text_element/templates/styles/text_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        $scripts = array();
        $scripts[] = $this->getTemplateEngine()->fetch("text_element/templates/scripts/text_element.js.tpl");
        return $scripts;
    }

}
