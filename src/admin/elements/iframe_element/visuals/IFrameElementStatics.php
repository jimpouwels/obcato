<?php

namespace Obcato\Core\admin\elements\iframe_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\Visual;

class IFrameElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/iframe_element/iframe_element_statics.tpl";
    }

    public function load(): void {}

}

?>