<?php

namespace Obcato\Core\elements\image_element\visuals;

use Obcato\Core\view\views\Visual;

class ImageElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "elements/image_element/image_element_statics.tpl";
    }

    public function load(): void {}

}