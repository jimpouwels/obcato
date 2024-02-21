<?php

namespace Obcato\Core\admin\elements\list_element\visuals;

use Obcato\Core\admin\view\views\Visual;

class ListElementStatics extends Visual {

    public function getTemplateFilename(): string {
        return "elements/list_element/list_element_statics.tpl";
    }

    public function load(): void {}

}