<?php

namespace Obcato\Core\admin\elements\form_element\visuals;

use Obcato\Core\admin\view\views\Visual;

class FormElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "elements/form_element/form_element_statics.tpl";
    }

    public function load(): void {}

}