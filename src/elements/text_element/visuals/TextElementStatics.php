<?php

namespace Obcato\Core\elements\text_element\visuals;

use Obcato\Core\view\views\Visual;

class TextElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "text_element/templates/text_element_statics.tpl";
    }

    public function load(): void {}

}
