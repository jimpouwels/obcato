<?php

class PhotoAlbumElementStatics extends Obcato\ComponentApi\Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/photo_album_element/photo_album_element_statics.tpl";
    }

    public function load(): void {}

}

?>