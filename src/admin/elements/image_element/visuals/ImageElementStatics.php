<?php

namespace Obcato\Core\admin\elements\image_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class ImageElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/image_element/image_element_statics.tpl";
    }

    public function load(): void {}

}