<?php

namespace Obcato\Core\admin\elements\list_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class ListElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/list_element/list_element_statics.tpl";
    }

    public function load(): void {}

}