<?php

namespace Pageflow\Core\elements\iframe_element\visuals;

use Pageflow\Core\view\views\ElementStatic;

class IFrameElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("iframe_element/templates/styles/iframe_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}

?>