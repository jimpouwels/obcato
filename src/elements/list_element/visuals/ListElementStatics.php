<?php

namespace Obcato\Core\elements\list_element\visuals;

use Obcato\Core\view\views\Visual;

class ListElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "elements/list_element/list_element_statics.tpl";
    }

    public function load(): void {}

}