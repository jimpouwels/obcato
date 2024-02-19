<?php

class IFrameElementStatics extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/iframe_element/iframe_element_statics.tpl";
    }

    public function load(): void {}

}

?>