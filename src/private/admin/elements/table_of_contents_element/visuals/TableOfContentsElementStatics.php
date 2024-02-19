<?php

class TableOfContentsElementStatics extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/table_of_contents_element/table_of_contents_element_statics.tpl";
    }

    public function load(): void {}

}

?>