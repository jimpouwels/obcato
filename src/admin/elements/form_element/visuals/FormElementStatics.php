<?php

namespace Obcato\Core\admin\elements\form_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\Visual;

class FormElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/form_element/form_element_statics.tpl";
    }

    public function load(): void {}

}