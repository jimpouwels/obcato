<?php

namespace Obcato\Core\elements\table_of_contents_element\visuals;

use Obcato\Core\view\views\ElementStatic;

class TableOfContentsElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("table_of_contents_element/templates/styles/table_of_contents_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}