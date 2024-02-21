<?php

namespace Obcato\Core\admin\elements\image_element\visuals;

use Obcato\Core\admin\view\views\Visual;

class ImageElementStatics extends Visual {

    public function getTemplateFilename(): string {
        return "elements/image_element/image_element_statics.tpl";
    }

    public function load(): void {}

}