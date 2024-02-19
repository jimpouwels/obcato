<?php

class ListElementStatics extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/list_element/list_element_statics.tpl";
    }

    public function load(): void {}

}