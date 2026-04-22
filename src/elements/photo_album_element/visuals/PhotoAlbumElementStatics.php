<?php

namespace Pageflow\Core\elements\photo_album_element\visuals;

use Pageflow\Core\view\views\ElementStatic;

class PhotoAlbumElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("photo_album_element/templates/styles/photo_album_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}