<?php

namespace Obcato\Core\elements\text_element\visuals;

use Obcato\Core\view\views\ElementStatic;

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
        return array();
    }

}
