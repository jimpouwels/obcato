<?php

namespace Obcato\Core\admin\elements\photo_album_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\view\views\Visual;

class PhotoAlbumElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/photo_album_element/photo_album_element_statics.tpl";
    }

    public function load(): void {}

}