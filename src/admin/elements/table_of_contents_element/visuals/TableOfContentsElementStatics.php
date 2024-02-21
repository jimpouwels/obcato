<?php

namespace Obcato\Core\admin\elements\table_of_contents_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\Visual;

class TableOfContentsElementStatics extends Visual {

    public function getTemplateFilename(): string {
        return "elements/table_of_contents_element/table_of_contents_element_statics.tpl";
    }

    public function load(): void {}

}