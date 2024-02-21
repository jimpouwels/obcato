<?php

namespace Obcato\Core\admin\elements\text_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class TextElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/text_element/text_element_statics.tpl";
    }

    public function load(): void {}

}
