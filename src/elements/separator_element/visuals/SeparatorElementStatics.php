<?php

namespace Obcato\Core\elements\separator_element\visuals;

use Obcato\Core\view\views\Visual;

class SeparatorElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "elements/separator_element/separator_element_statics.tpl";
    }

    public function load(): void {}

}