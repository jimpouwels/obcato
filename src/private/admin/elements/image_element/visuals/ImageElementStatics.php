<?php

class ImageElementStatics extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/image_element/image_element_statics.tpl";
    }

    public function load(): void {}

}

?>