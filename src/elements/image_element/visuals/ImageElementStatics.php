<?php

namespace Pageflow\Core\elements\image_element\visuals;

use Pageflow\Core\view\views\ElementStatic;

class ImageElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("image_element/templates/styles/image_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}