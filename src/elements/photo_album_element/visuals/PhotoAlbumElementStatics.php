<?php

namespace Obcato\Core\elements\photo_album_element\visuals;

use Obcato\Core\view\views\Visual;

class PhotoAlbumElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "photo_album_element/templates/photo_album_element_statics.tpl";
    }

    public function load(): void {}

}