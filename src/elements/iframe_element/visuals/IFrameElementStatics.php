<?php

namespace Obcato\Core\elements\iframe_element\visuals;

use Obcato\Core\view\views\Visual;

class IFrameElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "iframe_element/templates/iframe_element_statics.tpl";
    }

    public function load(): void {}

}

?>