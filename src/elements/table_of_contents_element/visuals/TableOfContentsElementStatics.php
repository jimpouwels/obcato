<?php

namespace Obcato\Core\elements\table_of_contents_element\visuals;

use Obcato\Core\view\views\Visual;

class TableOfContentsElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "table_of_contents_element/templates/table_of_contents_element_statics.tpl";
    }

    public function load(): void {}

}